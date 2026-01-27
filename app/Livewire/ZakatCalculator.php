<?php

namespace App\Livewire;

use Livewire\Component;

class ZakatCalculator extends Component
{
    public string $type = 'maal'; // maal, profesi, emas

    // Maal Fields
    public float $totalWealth = 0;

    // Profesi Fields
    public float $monthlySalary = 0;
    public float $otherIncome = 0;
    public float $monthlyExpenses = 0; // Optional, some schools include this

    // Emas Fields
    public float $goldWeight = 0;

    // Constants (Can be made dynamic later)
    public float $goldPrice = 1450000; // Harga emas per gram
    public float $nisabGold = 85; // 85 gram emas
    public float $zakatRate = 0.025; // 2.5%

    public function calculate()
    {
        // Handled reactively or on button click
    }

    public function getMaalZakatProperty()
    {
        $nisabValue = $this->nisabGold * $this->goldPrice;
        if ($this->totalWealth >= $nisabValue) {
            return $this->totalWealth * $this->zakatRate;
        }
        return 0;
    }

    public function getProfesiZakatProperty()
    {
        $totalMonthly = $this->monthlySalary + $this->otherIncome;
        // Nisab profesi setara 522kg beras atau sering dikonversi ke emas bulanan (85/12)
        $nisabMonthly = ($this->nisabGold * $this->goldPrice) / 12;

        if ($totalMonthly >= $nisabMonthly) {
            return $totalMonthly * $this->zakatRate;
        }
        return 0;
    }

    public function getEmasZakatProperty()
    {
        if ($this->goldWeight >= $this->nisabGold) {
            return ($this->goldWeight * $this->goldPrice) * $this->zakatRate;
        }
        return 0;
    }

    public function render()
    {
        return view('livewire.zakat-calculator')->layout('components.layouts.app');
    }
}
