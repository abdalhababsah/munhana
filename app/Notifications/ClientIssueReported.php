<?php

namespace App\Notifications;

use App\Models\WarrantyIssue;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientIssueReported extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public WarrantyIssue $issue)
    {
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $projectName = app()->getLocale() === 'ar'
            ? $this->issue->project?->name_ar
            : $this->issue->project?->name;

        return (new MailMessage)
            ->subject(__('messages.new_client_issue_reported'))
            ->line(__('messages.new_client_issue_body', ['project' => $projectName]))
            ->action(__('messages.view_issue'), route('backend.warranty-issues.show', $this->issue))
            ->line(__('messages.thank_you'));
    }
}
