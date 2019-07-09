<?php

namespace App;

trait BoosterPresenter
{
    public function getUndercutProfitEuroAttribute()
    {
        if ($this->offers->isEmpty()) {
            return 'No offers';
        }

        return number_format($this->undercut_profit / 100, 2) . ' €';
    }
}
