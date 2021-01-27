<?php
namespace App\Entities;

use Carbon\Carbon;

class History implements \JsonSerializable
{

    /**
     *
     * @param array $data
     * @return History
     */
    static public function deserialize(array $data): History
    {
        $instance = new History();
        $instance->id = empty($data['id']) ? null : (int) $data['id'];
        $instance->temp = empty($data['temp']) ? null : (float) $data['temp'];
        $instance->dateAt = empty($data['date']) ? null : Carbon::parse($data['date']);
        return $instance;
    }

    /**
     *
     * @var integer
     */
    private $id;

    /**
     *
     * @var float
     */
    private $temp;

    /**
     *
     * @var \Carbon\Carbon
     */
    private $dateAt;

    /**
     *
     * @return integer|null
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     *
     * @return float|null
     */
    public function getTemp()
    {
        return $this->temp;
    }

    /**
     *
     * @return \Carbon\Carbon|null
     */
    public function getDateAt()
    {
        return $this->dateAt;
    }

    /**
     *
     * @param float $temp
     */
    public function setTemp(float $temp)
    {
        $this->temp = $temp;
    }

    /**
     *
     * @param \Carbon\Carbon $dateAt
     */
    public function setDateAt(Carbon $dateAt)
    {
        $this->dateAt = $dateAt;
    }

    /**
     *
     * {@inheritdoc}
     * @see \JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'date' => $this->dateAt,
            'temp' => (float) $this->temp
        ];
    }
}
