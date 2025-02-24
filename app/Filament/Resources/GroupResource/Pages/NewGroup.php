<?php

namespace App\Filament\Resources\GroupResource\Pages;

use App\Filament\Resources\GroupResource;
use App\Models\Group;
use App\Models\GroupParticipant;
use App\Models\Groups;
use App\Models\GroupSetting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class NewGroup extends Page implements HasForms, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;
    use WithFileUploads;

    protected static string $resource = GroupResource::class;
    protected static string $view = 'filament.resources.group-resource.pages.new-group';
    public array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Section::make('Group Data')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->placeholder('Enter the group name')
                            ->required()
                            ->maxLength(255)
                            ->unique('groups', 'name'),
                        Textarea::make('description')
                            ->label('Description')
                            ->placeholder('Enter the group description')
                            ->required()
                            ->maxLength(1000),
                    ]),
                Section::make('Group Settings')
                    ->schema([
                        FileUpload::make('group_icon')
                            ->avatar()
                            ->label('Group Icon')
                            ->required()
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth('200')
                            ->imageResizeTargetHeight('200')
                            ->maxSize(5120) // 5MB limit
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->directory('temp/group-icons'),
                    ])
                    ->columns(2),
            ]);
    }

    public function create(): mixed
    {
        try {
            // Validate data before proceeding
            $validatedData = $this->form->getState();

            // Create the group with a UUID
            $group = new Groups();
            $group->group_id = (string) Str::uuid();
            $group->save();

            // Get the uploaded image
            $image = $validatedData['group_icon'];

            // Upload to Cloudinary and get URL
            $groupIcon = $this->uploadImage($image);
            if (!$groupIcon) {
                throw new \Exception("Failed to upload group icon.");
            }

            // Create related group settings
            GroupSetting::create([
                'group_id' => $group->id,
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'group_icon' => $groupIcon,
            ]);

            // Add the authenticated user as a participant
            GroupParticipant::create([
                'user_id' => Auth::id(),
                'group_id' => $group->id,
            ]);

            Notification::make()
                ->success()
                ->title('Group Created')
                ->body('The group has been created successfully.')
                ->send();

            $this->redirect(GroupResource::getUrl());
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to create group: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'data' => $this->data,
                'trace' => $e->getTraceAsString(),
            ]);

            Notification::make()
                ->danger()
                ->title('Error Creating Group')
                ->body('An error occurred while creating the group. Please try again.')
                ->send();

            return null;
        }
    }

    protected function uploadImage(string $imagePath): string
    {
        try {
            $fullPath = storage_path("app/public/{$imagePath}");

            if (!file_exists($fullPath)) {
                throw new \RuntimeException('Image file not found');
            }

            // Upload to Cloudinary with error handling
            $uploadedImage = cloudinary()->upload($fullPath, [
                'folder' => 'group/icons',
                'transformation' => [
                    'width' => 200,
                    'height' => 200,
                    'crop' => 'fill',
                    'quality' => 'auto',
                    'fetch_format' => 'auto',
                ],
                'resource_type' => 'image',
            ]);

            if (!$uploadedImage) {
                throw new \RuntimeException('Failed to upload image to Cloudinary');
            }

            // Clean up the temporary file
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }

            return $uploadedImage->getSecurePath();
        } catch (\Exception $e) {
            Log::error('Failed to upload image: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'image_path' => $imagePath,
                'trace' => $e->getTraceAsString(),
            ]);

            throw new \RuntimeException('Failed to upload group icon: ' . $e->getMessage());
        }
    }
}
