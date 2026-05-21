<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Note;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with sample data.
     */
    public function run(): void
    {
        // ── Demo User ──────────────────────────────────────────
        $user = User::firstOrCreate(
            ['email' => 'demo@smartnoteshub.com'],
            [
                'name'     => 'Demo User',
                'password' => Hash::make('password'),
            ]
        );

        // ── Tags ───────────────────────────────────────────────
        $tags = [];
        $tagData = [
            ['name' => 'work',       'color' => '#6366f1'],
            ['name' => 'personal',   'color' => '#10b981'],
            ['name' => 'ideas',      'color' => '#f59e0b'],
            ['name' => 'urgent',     'color' => '#ef4444'],
            ['name' => 'reference',  'color' => '#3b82f6'],
            ['name' => 'learning',   'color' => '#8b5cf6'],
            ['name' => 'health',     'color' => '#ec4899'],
            ['name' => 'finance',    'color' => '#f97316'],
        ];

        foreach ($tagData as $td) {
            $tags[$td['name']] = Tag::firstOrCreate(['name' => $td['name']], ['color' => $td['color']]);
        }

        // ── Folders ────────────────────────────────────────────
        $work     = Category::firstOrCreate(
            ['user_id' => $user->id, 'name' => 'Work'],
            ['color' => '#6366f1', 'icon' => 'briefcase']
        );
        $personal = Category::firstOrCreate(
            ['user_id' => $user->id, 'name' => 'Personal'],
            ['color' => '#10b981', 'icon' => 'heart']
        );
        $ideas    = Category::firstOrCreate(
            ['user_id' => $user->id, 'name' => 'Ideas'],
            ['color' => '#f59e0b', 'icon' => 'lightbulb']
        );

        // ── Notes ─────────────────────────────────────────────
        $notesData = [
            [
                'title'       => '📋 Q3 Project Roadmap',
                'content'     => '<h2>Q3 2024 Goals</h2><p>This quarter we are focused on three main pillars:</p><ul><li><strong>Product Launch</strong> – Ship v2.0 by end of July</li><li><strong>Customer Growth</strong> – Reach 10k active users</li><li><strong>Team Expansion</strong> – Hire 2 senior engineers</li></ul><p>Key milestones to track weekly in stand-ups.</p>',
                'priority'    => 'high',
                'is_pinned'   => true,
                'color'       => '#dbeafe',
                'category_id' => $work->id,
                'tags'        => ['work', 'urgent'],
            ],
            [
                'title'       => '💡 App Ideas Backlog',
                'content'     => '<h2>Product Ideas</h2><p>A running list of ideas to explore:</p><ol><li>AI-powered note summarizer</li><li>Voice-to-text transcription</li><li>Collaborative real-time editing</li><li>Browser extension for web clipping</li><li>Mobile app (iOS & Android)</li></ol>',
                'priority'    => 'medium',
                'is_pinned'   => false,
                'color'       => '#fef9c3',
                'category_id' => $ideas->id,
                'tags'        => ['ideas', 'work'],
            ],
            [
                'title'       => '📚 Laravel Best Practices',
                'content'     => '<h2>Key Laravel Concepts</h2><p>Notes from reading the Laravel documentation:</p><ul><li>Always use <code>App\Models</code> namespace</li><li>Use Form Requests for validation</li><li>Leverage Eloquent scopes for reusable queries</li><li>Use queues for slow operations</li></ul><blockquote>Clean code is not written, it is rewritten.</blockquote>',
                'priority'    => 'medium',
                'is_pinned'   => false,
                'color'       => '#dcfce7',
                'category_id' => null,
                'tags'        => ['learning', 'reference'],
            ],
            [
                'title'       => '🏋️ Weekly Workout Plan',
                'content'     => '<h2>This Week\'s Training</h2><p><strong>Monday</strong> – Upper body (chest, shoulders, triceps)<br><strong>Tuesday</strong> – Run 5km easy<br><strong>Wednesday</strong> – Lower body (squats, lunges)<br><strong>Thursday</strong> – Rest or yoga<br><strong>Friday</strong> – Full body HIIT<br><strong>Saturday</strong> – Long run 10km<br><strong>Sunday</strong> – Active recovery</p>',
                'priority'    => 'low',
                'is_pinned'   => false,
                'color'       => '#fce7f3',
                'category_id' => $personal->id,
                'tags'        => ['health', 'personal'],
            ],
            [
                'title'       => '💰 Monthly Budget',
                'content'     => '<h2>June 2024 Budget</h2><p>Income: <strong>$5,000</strong></p><p>Fixed Expenses:</p><ul><li>Rent – $1,200</li><li>Subscriptions – $85</li><li>Insurance – $200</li></ul><p>Variable Budget: $800 for food, $300 for entertainment.</p><p>Savings Goal: <strong>$1,000/month</strong> 🎯</p>',
                'priority'    => 'medium',
                'is_pinned'   => false,
                'color'       => '#ffedd5',
                'category_id' => $personal->id,
                'tags'        => ['finance', 'personal'],
            ],
            [
                'title'       => '🔑 Meeting Notes – Product Sync',
                'content'     => '<h2>Product Sync – May 20, 2024</h2><p><strong>Attendees:</strong> Alice, Bob, Carol, Dave</p><p><strong>Agenda:</strong></p><ol><li>Sprint review & retrospective</li><li>Q3 priorities alignment</li><li>Design handoff process</li></ol><p><strong>Action Items:</strong></p><ul><li>Alice – Update Figma designs by Friday</li><li>Bob – Fix auth bug before next release</li><li>Carol – Set up staging environment</li></ul>',
                'priority'    => 'high',
                'is_pinned'   => false,
                'color'       => '#ede9fe',
                'category_id' => $work->id,
                'tags'        => ['work', 'urgent'],
            ],
        ];

        foreach ($notesData as $nd) {
            $noteTags = $nd['tags'];
            unset($nd['tags']);

            $note = Note::create(array_merge($nd, ['user_id' => $user->id]));

            $tagIds = [];
            foreach ($noteTags as $tagName) {
                if (isset($tags[$tagName])) {
                    $tagIds[] = $tags[$tagName]->id;
                }
            }
            $note->tags()->sync($tagIds);
        }

        $this->command->info('✅ Smart Notes Hub seeded successfully!');
        $this->command->info('📧 Login: demo@smartnoteshub.com');
        $this->command->info('🔐 Password: password');
    }
}
