<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use App\Rules\ValidRussianPassport;
use App\Rules\ValidRussianINN;
use App\Rules\ValidRussianPhone;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function updateName(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return back()->with('success', 'Имя успешно обновлено');
    }

    public function updateForemanData(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['nullable', 'string', 'max:255', 'regex:/^[\p{Cyrillic}\s\-]+$/u'],
            'address' => ['nullable', 'string', 'max:500'],
            'passport_series' => ['nullable', 'string', new ValidRussianPassport('series')],
            'passport_number' => ['nullable', 'string', new ValidRussianPassport('number')],
            'passport_issued_by' => ['nullable', 'string', 'max:255'],
            'passport_issued_date' => ['nullable', 'date', 'before:today', 'after:1950-01-01'],
            'inn' => ['nullable', 'string', new ValidRussianINN()],
            'organization_name' => ['nullable', 'string', 'max:255'],
        ], [
            'full_name.regex' => 'ФИО должно содержать только кириллицу, пробелы и дефисы.',
            'passport_issued_date.before' => 'Дата выдачи паспорта не может быть в будущем.',
            'passport_issued_date.after' => 'Неверная дата выдачи паспорта.',
        ]);

        $user = Auth::user();
        $user->update($validated);

        return back()->with('success', 'Данные прораба успешно обновлены');
    }

    public function sendEmailVerificationCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
        ]);

        $user = Auth::user();
        $code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(15);

        // Удаляем старые неподтвержденные коды
        DB::table('email_verification_codes')
            ->where('user_id', $user->id)
            ->where('verified', false)
            ->delete();

        // Создаем новый код
        DB::table('email_verification_codes')->insert([
            'user_id' => $user->id,
            'email' => $request->email,
            'code' => $code,
            'expires_at' => $expiresAt,
            'verified' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Отправка письма
        $to = $request->email;
        $subject = 'Код подтверждения email';
        $message = "Ваш код подтверждения: {$code}\n\nКод действителен в течение 15 минут.";
        $headers = "From: noreply@objectplus\r\n" .
                   "Content-Type: text/plain; charset=UTF-8\r\n";

        mail($to, $subject, $message, $headers);

        return response()->json([
            'success' => true,
            'message' => 'Код отправлен на указанный email',
        ]);
    }

    public function verifyEmailCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:4'],
        ]);

        $user = Auth::user();

        $verification = DB::table('email_verification_codes')
            ->where('user_id', $user->id)
            ->where('code', $request->code)
            ->where('verified', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return response()->json([
                'success' => false,
                'message' => 'Неверный или истекший код',
            ], 422);
        }

        // Обновляем email пользователя
        $user->email = $verification->email;
        $user->save();

        // Помечаем код как использованный
        DB::table('email_verification_codes')
            ->where('id', $verification->id)
            ->update(['verified' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Email успешно обновлен',
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Пароль успешно изменен');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();
        
        // Удаляем все связанные данные
        DB::transaction(function () use ($user) {
            // Удаляем проекты пользователя
            $user->projects()->delete();
            
            // Удаляем коды верификации
            DB::table('email_verification_codes')
                ->where('user_id', $user->id)
                ->delete();
            
            // Выход из системы
            Auth::logout();
            
            // Удаляем самого пользователя
            $user->delete();
        });

        return redirect()->route('login')->with('success', 'Ваш аккаунт был успешно удален');
    }

    public function selectRole(Request $request)
    {
        $user = Auth::user();
        
        // Если пользователь уже сделал выбор, не даем изменить
        if ($user->role_selected) {
            return redirect()->route('home');
        }

        $isForeman = $request->input('is_foreman') == '1';
        
        // Отмечаем, что пользователь сделал выбор
        $user->role_selected = true;
        $user->save();

        // Если выбрал "Я прораб" - перенаправляем на страницу тарифов
        if ($isForeman) {
            return redirect()->route('pricing.index');
        }

        // Если выбрал "Я не прораб" - просто закрываем модалку и остаемся на главной
        return redirect()->route('home');
    }
}
