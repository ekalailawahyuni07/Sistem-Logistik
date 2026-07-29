<?php

namespace App\Mail;

use App\Models\TransaksiMaterial;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MaterialMasukMail extends Mailable
{
    use Queueable, SerializesModels;

    public TransaksiMaterial $transaksi;

    public function __construct(TransaksiMaterial $transaksi)
    {
        $this->transaksi = $transaksi;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[MATRILOG] Notifikasi Material Masuk',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.material-masuk',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}