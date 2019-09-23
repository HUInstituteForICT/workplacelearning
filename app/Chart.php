<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class Chart holds the data required for plotting the charts and provides easy access to the data.
 */
class Chart
{
    // Holds chart data
    private $data = [];

    public function __construct($labels, $data)
    {
        $this->data['labels'] = collect($labels)->map(function ($label) {
            return __($label);
        });
        $this->data['data'] = collect($data);
    }

    /**
     * @param $name
     *
     * @return Collection
     *
     * @throws \Exception
     */
    public function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        throw new \Exception('Property does not exist');
    }
}
