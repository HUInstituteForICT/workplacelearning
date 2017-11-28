<?php


namespace App\Tips;


use Illuminate\Database\Eloquent\Model;


/**
 * @property Statistic $statistic The statistic used for this tip
 * @property float $threshold The threshold that determines whether the tip is applicable or not
 * @property string $tipText The text including placeholders used for displaying the tip
 * @property boolean $multiplyBy100 Whether or not the percentage calculated should be multiplied by 100 in the getTipText
 */
class Tip extends Model
{

    /**
     * The result will be newly cached after every call to the "isApplicable". This to counteract recalculating on a "getTipText" call.
     * We can safely assume the "getTipText" call immediately follows the "isApplicable" call.
     * @var float|int $cachedResult
     */
    private $cachedResult;

    /**
     * The statistic used for this tip
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function statistic() {
        return $this->hasOne(Statistic::class);
    }

    /**
     * Check if the tip is applicable
     * @return bool
     */
    public function isApplicable()
    {
        $this->cachedResult = $this->statistic->calculate();

        return $this->cachedResult >= $this->threshold;
    }

    /**
     * Get the tipText with calculated data
     * @return string
     */
    public function getTipText()
    {
        $tipText = $this->tipText;
        $percentageValue = $this->multiplyBy100 ? number_format($this->cachedResult * 100) : $this->cachedResult;
        $tipText = str_replace(':percentage', $percentageValue, $tipText);

        return $tipText;
    }

}