<?php

namespace App\Mail;

use App\Models\Material;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StokMenipisMail extends Mailable
{
    use Queueable, SerializesModels;

    public Material $material;

    public function __construct(Material $material)
    {
        $this->material = $material;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[PERINGATAN MATRILOG] Stok Material Menipis',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.stok-menipis',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}