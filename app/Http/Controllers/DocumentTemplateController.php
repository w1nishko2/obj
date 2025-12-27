<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\DocumentTemplate;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use App\Rules\ValidRussianPassport;
use App\Rules\ValidRussianINN;
use App\Rules\ValidRussianPhone;

class DocumentTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Показать список доступных шаблонов для проекта
     */
    public function index(Project $project)
    {
        $this->authorize('view', $project);

        if (!auth()->user()->isForeman()) {
            abort(403, 'Доступ к шаблонам документов разрешен только прорабам');
        }

        $templates = DocumentTemplate::where('is_active', true)->get();

        return view('document-templates.index', compact('project', 'templates'));
    }

    /**
     * Сгенерировать документ по шаблону
     */
    public function generate(Request $request, Project $project, DocumentTemplate $template)
    {
        $this->authorize('view', $project);

        if (!auth()->user()->isForeman()) {
            abort(403, 'Доступ к генерации документов разрешен только прорабам');
        }

        // Проверяем, может ли пользователь генерировать документы
        if (!auth()->user()->canGenerateDocuments()) {
            return redirect()->back()->with('error', 'Генерация документов доступна только на платных тарифах. Оформите подписку для доступа к этой функции.');
        }

        // Загружаем все необходимые данные проекта
        $project->load(['stages.tasks.assignedUser', 'stages.materials.user', 'stages.deliveries.user', 'user']);

        // Подготавливаем данные для подстановки
        $data = $this->prepareDocumentData($project);

        // Заменяем плейсхолдеры в содержимом шаблона
        $content = $this->replacePlaceholders($template->content, $data);

        // Генерируем PDF
        $pdf = Pdf::loadHTML($content);
        
        $fileName = Str::slug($template->name) . '_' . Str::slug($project->name) . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($fileName);
    }

    /**
     * Подготовка данных проекта для подстановки в шаблон
     */
    private function prepareDocumentData(Project $project)
    {
        // Базовые данные проекта
        $data = [
            // Информация о проекте
            'project_name' => $project->name ?? '',
            'project_address' => $project->address ?? '',
            'project_work_type' => $project->work_type ?? '',
            'project_status' => $project->status ?? '',
            'project_created_date' => $project->created_at ? $project->created_at->format('d.m.Y') : '',
            
            // Данные клиента
            'client_full_name' => $project->client_full_name ?? '',
            'client_phone' => $project->client_phone ?? '',
            'client_email' => $project->client_email ?? '',
            'client_address' => $project->client_address ?? '',
            'client_passport_series' => $project->client_passport_series ?? '',
            'client_passport_number' => $project->client_passport_number ?? '',
            'client_passport_issued_by' => $project->client_passport_issued_by ?? '',
            'client_passport_issued_date' => $project->client_passport_issued_date ? $project->client_passport_issued_date->format('d.m.Y') : '',
            'client_inn' => $project->client_inn ?? '',
            'client_organization_name' => $project->client_organization_name ?? '',
            
            // Данные прораба (приоритет - данные из проекта, если нет - из пользователя)
            'foreman_full_name' => $project->foreman_full_name ?? $project->user->name ?? '',
            'foreman_name' => $project->foreman_full_name ?? $project->user->name ?? '',
            'foreman_email' => $project->foreman_email ?? $project->user->email ?? '',
            'foreman_phone' => $project->foreman_phone ?? $project->user->phone ?? '',
            'foreman_address' => $project->foreman_address ?? '',
            'foreman_passport_series' => $project->foreman_passport_series ?? '',
            'foreman_passport_number' => $project->foreman_passport_number ?? '',
            'foreman_passport_issued_by' => $project->foreman_passport_issued_by ?? '',
            'foreman_passport_issued_date' => $project->foreman_passport_issued_date ? $project->foreman_passport_issued_date->format('d.m.Y') : '',
            'foreman_inn' => $project->foreman_inn ?? '',
            'foreman_organization_name' => $project->foreman_organization_name ?? '',
            
            // Финансовые данные
            'total_cost' => number_format($project->total_cost, 2, ',', ' '),
            
            // Текущая дата
            'current_date' => date('d.m.Y'),
            'current_datetime' => date('d.m.Y H:i'),
        ];

        // Данные по этапам и задачам
        $stagesHtml = $this->generateStagesTable($project);
        $data['stages_table'] = $stagesHtml;

        return $data;
    }

    /**
     * Генерация HTML-таблицы этапов и задач
     */
    private function generateStagesTable(Project $project)
    {
        $html = '<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">';
        $html .= '<thead>';
        $html .= '<tr style="background: #f0f0f0;">';
        $html .= '<th style="border: 1px solid #000; padding: 8px; text-align: left;">№</th>';
        $html .= '<th style="border: 1px solid #000; padding: 8px; text-align: left;">Наименование</th>';
        $html .= '<th style="border: 1px solid #000; padding: 8px; text-align: left;">Статус</th>';
        $html .= '<th style="border: 1px solid #000; padding: 8px; text-align: right;">Стоимость (₽)</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        $number = 1;
        $totalCost = 0;

        foreach ($project->stages as $stage) {
            // Строка этапа
            $stageCost = 0;
            
            // Считаем стоимость задач этапа
            foreach ($stage->tasks as $task) {
                $stageCost += $task->final_cost ?? 0;
            }
            
            // Считаем стоимость материалов этапа
            foreach ($stage->materials as $material) {
                $stageCost += $material->final_cost ?? 0;
            }
            
            // Считаем стоимость доставок этапа
            foreach ($stage->deliveries as $delivery) {
                $stageCost += $delivery->final_cost ?? 0;
            }

            $html .= '<tr>';
            $html .= '<td style="border: 1px solid #000; padding: 8px;">' . $number++ . '</td>';
            $html .= '<td style="border: 1px solid #000; padding: 8px;"><strong>' . htmlspecialchars($stage->name) . '</strong></td>';
            $html .= '<td style="border: 1px solid #000; padding: 8px;">' . htmlspecialchars($stage->status) . '</td>';
            $html .= '<td style="border: 1px solid #000; padding: 8px; text-align: right;">' . number_format($stageCost, 2, ',', ' ') . '</td>';
            $html .= '</tr>';

            // Задачи этапа
            foreach ($stage->tasks as $task) {
                if ($task->final_cost > 0) {
                    $html .= '<tr>';
                    $html .= '<td style="border: 1px solid #000; padding: 8px; padding-left: 20px;"></td>';
                    $html .= '<td style="border: 1px solid #000; padding: 8px; padding-left: 20px;">' . htmlspecialchars($task->name) . '</td>';
                    $html .= '<td style="border: 1px solid #000; padding: 8px;">' . htmlspecialchars($task->status) . '</td>';
                    $html .= '<td style="border: 1px solid #000; padding: 8px; text-align: right;">' . number_format($task->final_cost, 2, ',', ' ') . '</td>';
                    $html .= '</tr>';
                }
            }

            // Материалы этапа
            foreach ($stage->materials as $material) {
                if ($material->final_cost > 0) {
                    $html .= '<tr>';
                    $html .= '<td style="border: 1px solid #000; padding: 8px; padding-left: 20px;"></td>';
                    $html .= '<td style="border: 1px solid #000; padding: 8px; padding-left: 20px;">' . htmlspecialchars($material->name) . ' (' . $material->quantity . ' ' . $material->unit . ')</td>';
                    $html .= '<td style="border: 1px solid #000; padding: 8px;">Материал</td>';
                    $html .= '<td style="border: 1px solid #000; padding: 8px; text-align: right;">' . number_format($material->final_cost, 2, ',', ' ') . '</td>';
                    $html .= '</tr>';
                }
            }
            
            // Доставки этапа
            foreach ($stage->deliveries as $delivery) {
                if ($delivery->final_cost > 0) {
                    $html .= '<tr>';
                    $html .= '<td style="border: 1px solid #000; padding: 8px; padding-left: 20px;"></td>';
                    $html .= '<td style="border: 1px solid #000; padding: 8px; padding-left: 20px;">' . htmlspecialchars($delivery->name) . ' (' . $delivery->quantity . ' ' . $delivery->unit . ')</td>';
                    $html .= '<td style="border: 1px solid #000; padding: 8px;">Доставка</td>';
                    $html .= '<td style="border: 1px solid #000; padding: 8px; text-align: right;">' . number_format($delivery->final_cost, 2, ',', ' ') . '</td>';
                    $html .= '</tr>';
                }
            }

            $totalCost += $stageCost;
        }

        // Итоговая строка
        $html .= '<tr style="background: #f0f0f0; font-weight: bold;">';
        $html .= '<td colspan="3" style="border: 1px solid #000; padding: 8px; text-align: right;">ИТОГО:</td>';
        $html .= '<td style="border: 1px solid #000; padding: 8px; text-align: right;">' . number_format($totalCost, 2, ',', ' ') . '</td>';
        $html .= '</tr>';

        $html .= '</tbody>';
        $html .= '</table>';

        return $html;
    }

    /**
     * Замена плейсхолдеров в тексте
     */
    private function replacePlaceholders($content, $data)
    {
        foreach ($data as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }

        return $content;
    }

    /**
     * Показать форму редактирования данных клиента
     */
    public function editClientData(Project $project)
    {
        $this->authorize('update', $project);

        if (!auth()->user()->isForeman()) {
            abort(403, 'Доступ к редактированию данных клиента разрешен только прорабам');
        }

        // Предзаполняем данные прораба из профиля пользователя, если они не заполнены в проекте
        if (!$project->foreman_full_name && auth()->user()->full_name) {
            $project->foreman_full_name = auth()->user()->full_name;
        }
        if (!$project->foreman_phone && auth()->user()->phone) {
            $project->foreman_phone = auth()->user()->phone;
        }
        if (!$project->foreman_email && auth()->user()->email) {
            $project->foreman_email = auth()->user()->email;
        }
        if (!$project->foreman_address && auth()->user()->address) {
            $project->foreman_address = auth()->user()->address;
        }
        if (!$project->foreman_passport_series && auth()->user()->passport_series) {
            $project->foreman_passport_series = auth()->user()->passport_series;
        }
        if (!$project->foreman_passport_number && auth()->user()->passport_number) {
            $project->foreman_passport_number = auth()->user()->passport_number;
        }
        if (!$project->foreman_passport_issued_by && auth()->user()->passport_issued_by) {
            $project->foreman_passport_issued_by = auth()->user()->passport_issued_by;
        }
        if (!$project->foreman_passport_issued_date && auth()->user()->passport_issued_date) {
            $project->foreman_passport_issued_date = auth()->user()->passport_issued_date;
        }
        if (!$project->foreman_inn && auth()->user()->inn) {
            $project->foreman_inn = auth()->user()->inn;
        }
        if (!$project->foreman_organization_name && auth()->user()->organization_name) {
            $project->foreman_organization_name = auth()->user()->organization_name;
        }

        return view('document-templates.edit-client', compact('project'));
    }

    /**
     * Обновить данные клиента проекта
     */
    public function updateClientData(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        if (!auth()->user()->isForeman()) {
            abort(403, 'Доступ к обновлению данных клиента разрешен только прорабам');
        }

        $validated = $request->validate([
            // Данные клиента
            'client_full_name' => ['nullable', 'string', 'max:255', 'regex:/^[\p{Cyrillic}\s\-]+$/u'],
            'client_phone' => ['nullable', 'string', new ValidRussianPhone()],
            'client_email' => ['nullable', 'email:rfc,dns', 'max:255'],
            'client_address' => ['nullable', 'string', 'max:500'],
            'client_passport_series' => ['nullable', 'string', new ValidRussianPassport('series')],
            'client_passport_number' => ['nullable', 'string', new ValidRussianPassport('number')],
            'client_passport_issued_by' => ['nullable', 'string', 'max:255'],
            'client_passport_issued_date' => ['nullable', 'date', 'before:today', 'after:1950-01-01'],
            'client_inn' => ['nullable', 'string', new ValidRussianINN()],
            'client_organization_name' => ['nullable', 'string', 'max:255'],
            // Данные прораба
            'foreman_full_name' => ['nullable', 'string', 'max:255', 'regex:/^[\p{Cyrillic}\s\-]+$/u'],
            'foreman_phone' => ['nullable', 'string', new ValidRussianPhone()],
            'foreman_email' => ['nullable', 'email:rfc,dns', 'max:255'],
            'foreman_address' => ['nullable', 'string', 'max:500'],
            'foreman_passport_series' => ['nullable', 'string', new ValidRussianPassport('series')],
            'foreman_passport_number' => ['nullable', 'string', new ValidRussianPassport('number')],
            'foreman_passport_issued_by' => ['nullable', 'string', 'max:255'],
            'foreman_passport_issued_date' => ['nullable', 'date', 'before:today', 'after:1950-01-01'],
            'foreman_inn' => ['nullable', 'string', new ValidRussianINN()],
            'foreman_organization_name' => ['nullable', 'string', 'max:255'],
        ], [
            '*.regex' => 'ФИО должно содержать только кириллицу, пробелы и дефисы.',
            '*.passport_issued_date.before' => 'Дата выдачи паспорта не может быть в будущем.',
            '*.passport_issued_date.after' => 'Неверная дата выдачи паспорта.',
            '*.email' => 'Неверный формат email адреса.',
        ]);

        // Сохраняем данные прораба в таблицу users для переиспользования
        $user = auth()->user();
        $user->update([
            'full_name' => $request->foreman_full_name,
            'address' => $request->foreman_address,
            'passport_series' => $request->foreman_passport_series,
            'passport_number' => $request->foreman_passport_number,
            'passport_issued_by' => $request->foreman_passport_issued_by,
            'passport_issued_date' => $request->foreman_passport_issued_date,
            'inn' => $request->foreman_inn,
            'organization_name' => $request->foreman_organization_name,
        ]);

        // Сохраняем все данные в проект
        $project->update($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Данные клиента успешно обновлены!')
            ->with('tab', 'documents');
    }
}
