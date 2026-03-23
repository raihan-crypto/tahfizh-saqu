<?php

namespace App\Observers;

use App\Models\Setoran;
use App\Models\Santri;

class SetoranObserver
{
    public function created(Setoran $setoran): void
    {
        $this->updateSantriTotal($setoran->santri_id);
    }

    public function updated(Setoran $setoran): void
    {
        $this->updateSantriTotal($setoran->santri_id);
        
        // Handle if santri_id changes
        if ($setoran->isDirty('santri_id')) {
            $this->updateSantriTotal($setoran->getOriginal('santri_id'));
        }
    }

    public function deleted(Setoran $setoran): void
    {
        $this->updateSantriTotal($setoran->santri_id);
    }
    
    public function restored(Setoran $setoran): void
    {
        $this->updateSantriTotal($setoran->santri_id);
    }
    
    public function forceDeleted(Setoran $setoran): void
    {
        $this->updateSantriTotal($setoran->santri_id);
    }

    private function updateSantriTotal($santriId): void
    {
        if (!$santriId) return;

        // Kami definisikan progres dashboard dari ziyadah_baris (Hafalan Baru)
        $total = Setoran::where('santri_id', $santriId)->sum('ziyadah_baris');
        
        Santri::where('id', $santriId)->update(['total_hafalan_baris' => $total]);
    }
}
