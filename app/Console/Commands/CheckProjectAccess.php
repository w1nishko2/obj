<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectUserRole;
use App\Models\ProjectParticipant;

class CheckProjectAccess extends Command
{
    protected $signature = 'project:check-access {userId} {projectId}';
    protected $description = 'Check user access to project';

    public function handle()
    {
        $userId = $this->argument('userId');
        $projectId = $this->argument('projectId');

        $user = User::find($userId);
        $project = Project::find($projectId);

        if (!$user) {
            $this->error("User not found: {$userId}");
            return;
        }

        if (!$project) {
            $this->error("Project not found: {$projectId}");
            return;
        }

        $this->info("=== USER INFO ===");
        $this->line("ID: {$user->id}");
        $this->line("Name: {$user->name}");
        $this->line("Phone: {$user->phone}");

        $this->info("\n=== PROJECT INFO ===");
        $this->line("ID: {$project->id}");
        $this->line("Name: {$project->name}");
        $this->line("Owner ID: {$project->user_id}");

        $userRole = ProjectUserRole::where('project_id', $projectId)
            ->where('user_id', $userId)
            ->first();

        $this->info("\n=== USER ROLE IN PROJECT ===");
        if ($userRole) {
            $this->line("Role ID: {$userRole->id}");
            $this->line("Role: {$userRole->role}");
            $this->line("Can edit project: " . ($userRole->can_edit_project ? 'yes' : 'no'));
            $this->line("Can manage team: " . ($userRole->can_manage_team ? 'yes' : 'no'));
        } else {
            $this->error("NO ROLE FOUND - User does NOT have access!");
        }

        $participant = ProjectParticipant::where('project_id', $projectId)
            ->where('user_id', $userId)
            ->first();

        $this->info("\n=== USER AS PARTICIPANT ===");
        if ($participant) {
            $this->line("Participant ID: {$participant->id}");
            $this->line("Name: {$participant->name}");
            $this->line("Phone: {$participant->phone}");
        } else {
            $this->warn("Not in participants table");
        }

        $this->info("\n=== ALL USERS IN PROJECT ===");
        $allUsers = User::select('id', 'name', 'phone')->get();
        foreach ($allUsers as $u) {
            $this->line("ID: {$u->id}, Name: {$u->name}, Phone: {$u->phone}");
        }

        $this->info("\n=== ALL ROLES IN THIS PROJECT ===");
        $allRoles = ProjectUserRole::where('project_id', $projectId)->get();
        foreach ($allRoles as $role) {
            $roleUser = User::find($role->user_id);
            $this->line("User: {$roleUser->name} (ID: {$role->user_id}), Role: {$role->role}");
        }

        $this->info("\n=== ALL PARTICIPANTS IN THIS PROJECT ===");
        $allParticipants = ProjectParticipant::where('project_id', $projectId)->get();
        foreach ($allParticipants as $p) {
            $this->line("Name: {$p->name}, Phone: {$p->phone}, User ID: " . ($p->user_id ?? 'NULL'));
        }
    }
}
