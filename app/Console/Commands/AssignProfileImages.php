<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Organizer;
use Illuminate\Console\Command;
use Illuminate\Http\File as HttpFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AssignProfileImages extends Command
{
    protected $signature = 'profiles:assign-images';

    protected $description = 'Assign images from public/photos/users to users and organizers and update storage paths';

    public function handle()
    {
        $this->info('Starting profile image assignment...');

        $sourceDir = public_path('photos/users');
        if (!File::exists($sourceDir)) {
            $this->error("Source directory not found: {$sourceDir}");
            return;
        }

        $files = File::files($sourceDir);
        $images = array_filter($files, function ($file) {
            return in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp', 'gif']);
        });

        if (empty($images)) {
            $this->error('No images found in public/photos/users.');
            return;
        }

        $images = array_values($images);
        $imageCount = count($images);
        $this->info("Found {$imageCount} images to use.");

        if (!Storage::disk('public')->exists('profile_pics')) {
            Storage::disk('public')->makeDirectory('profile_pics');
        }
        if (!Storage::disk('public')->exists('organizers')) {
            Storage::disk('public')->makeDirectory('organizers');
        }

        $users = User::orderBy('id')->get();
        $organizers = Organizer::orderBy('id')->get();

        $this->info("Assigning photos to {$users->count()} users and {$organizers->count()} organizers.");

        $bar = $this->output->createProgressBar($users->count() + $organizers->count());
        $bar->start();

        foreach ($users as $index => $user) {
            $sourceImage = $images[$index % $imageCount];
            $path = Storage::disk('public')->putFile('profile_pics', new HttpFile($sourceImage->getPathname()));

            if ($user->profile_pic && Storage::disk('public')->exists($user->profile_pic)) {
                Storage::disk('public')->delete($user->profile_pic);
            }

            $user->profile_pic = $path;
            $user->save();
            $bar->advance();
        }

        foreach ($organizers as $index => $organizer) {
            $sourceImage = $images[$index % $imageCount];
            $path = Storage::disk('public')->putFile('organizers', new HttpFile($sourceImage->getPathname()));

            if ($organizer->organizer_image && Storage::disk('public')->exists($organizer->organizer_image)) {
                Storage::disk('public')->delete($organizer->organizer_image);
            }

            $organizer->organizer_image = $path;
            $organizer->save();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Profile images assigned successfully.');
        $this->info('Run "php artisan storage:link" if images do not appear.');
    }
}
