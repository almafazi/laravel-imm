<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $material_stock, $stock, $type;
    /**
     * Create a new notification instance.
     */
    public function __construct($material_stock, $stock, $type)
    {
        $this->material_stock = $material_stock;
        $this->stock = $stock;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting("Hi Admin!")
            ->subject('Perubahan Stok Ke Barang: ' . $this->material_stock->material->name)
            ->line('Ada perubahan stok barang ' . $this->material_stock->material->name)
            ->line('Detail Stock: ')
            ->line('Jumlah Stock: ' . $this->stock)
            ->line('Kode Produksi: ' . $this->material_stock->code)
            ->line('Timestamp: ' . $this->material_stock->created_at)
            ->action('Cek Disini', url('/'))
            ->line('Terimakasih kaka!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        if ($this->type == 'increase') {
            return [
                'title' => 'Penambahan Stok: ' . $this->material_stock->material->name,
                'stock' => $this->stock,
                'code' => $this->material_stock->code,
                'timestamp' => $this->material_stock->created_at
            ];
        }
        if ($this->type == 'decrease') {
            return [
                'title' => 'Pengurangan Stok: ' . $this->material_stock->material->name,
                'stock' => $this->stock,
                'code' => $this->material_stock->code,
                'timestamp' => $this->material_stock->created_at
            ];
        }
        if ($this->type == 'new_stock') {
            return [
                'title' => 'Stok awal Ke Barang: ' . $this->material_stock->material->name,
                'stock' => $this->stock,
                'code' => $this->material_stock->code,
                'timestamp' => $this->material_stock->created_at
            ];
        }
    }
}
