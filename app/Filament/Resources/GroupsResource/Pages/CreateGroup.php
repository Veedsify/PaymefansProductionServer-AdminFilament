<?php

namespace App\Filament\Resources\GroupsResource\Pages;

use App\Filament\Resources\GroupsResource;
use App\Models\Group;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class CreateGroup extends Page implements HasForms, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;
    protected static string $resource = GroupsResource::class;
    public $data = [];
    public function mount(): void
    {
        if (Group::all()->count() >= 1) {
            $this->redirect(GroupsResource::getUrl('index'));
            Notification::make()
                ->title('Group creation limit reached')
                ->body('You can only create one group at a time.')
                ->warning()
                ->send();
        }
        $this->form->fill();
    }

    public function getTitle(): string
    {
        return 'Create Group';
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Section::make("Create New Group")
                    ->description("Fill in the details to create a new group.")
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->columnSpanFull()
                            ->label('Group Name')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->columnSpanFull()
                            ->label('Description')
                            ->maxLength(500),
                        FileUpload::make('groupIcon')
                            ->image()
                            ->avatar()
                            ->label('Group Icon')
                            ->directory("groups/icon")
                            ->disk("s3")
                            ->visibility("publico")
                            ->image()
                            ->required(),
                        Grid::make(3)->schema([
                            Select::make('groupType')
                                ->label('Group Type')
                                ->native(false)
                                ->options([
                                    'PUBLIC' => 'Public',
                                    // 'PRIVATE' => 'Private',
                                    // 'SECRET' => 'Secret'
                                ]),
                            TextInput::make('maxMembers')
                                ->label('Max Members')
                                ->numeric()
                                ->default(100),
                        ]),
                    ]),
                Section::make("Group Settings")
                    ->description("Configure additional settings for the group.")
                    ->columns(2)
                    ->schema([
                        Toggle::make('allowFileSharing')
                            ->label('Allow File Sharing')
                            ->default(true),
                        Toggle::make('allowMediaSharing')
                            ->label('Allow Media Sharing')
                            ->default(true),
                        Toggle::make('allowMemberInvites')
                            ->label('Allow Member Invites')
                            ->default(true),
                        Toggle::make('autoApproveJoinReqs')
                            ->label('Auto Approve Join Requests')
                            ->default(false),
                        Toggle::make('moderateMessages')
                            ->label('Moderate Messages')
                            ->default(false),
                    ]),
            ]);
    }


    public function createGroup(): void
    {
        $cloudfrontUrl = env('AWS_CLOUDFRONT_URL', 'https://d2389neb6gppcb.cloudfront.net');
        $data = $this->form->getState();
        $newGroup = GroupsResource::getModel()::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'groupIcon' => $cloudfrontUrl . '/' . $data['groupIcon'],
            'groupType' => $data['groupType'],
            'maxMembers' => $data['maxMembers'],
            'isActive' => true, // Default to active
            'adminId' => Auth::id(), // Assuming the admin is the currently authenticated user
        ]);
        // Create default group settings
        $newGroup->settings()->create([
            'allowFileSharing' => $data['allowFileSharing'],
            'allowMediaSharing' => $data['allowMediaSharing'],
            'allowMemberInvites' => $data['allowMemberInvites'],
            'autoApproveJoinReqs' => $data['autoApproveJoinReqs'],
            'moderateMessages' => $data['moderateMessages'],
            'mutedUntil' => null, // Default value, can be set later
        ]);
        $newGroup->members()->create([
            'userId' => Auth::id(), // Add the admin as the first member
            'role' => "ADMIN", // The creator is the admin
            'joinedAt' => now(),
            'lastSeen' => now(),
            'isMuted' => false, // Default to not muted
            'mutedBy' => null, // No one has muted the admin yet
            'mutedUntil' => null, // No mute duration set
        ]);
        // Redirect or show a success message after creation
        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
        $this->redirect(GroupsResource::getUrl('index'));
    }

    protected static string $view = 'filament.resources.groups-resource.pages.create-group';
}
