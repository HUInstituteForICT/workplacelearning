<?php

namespace App;

use App\Exceptions\UnknownLearningItemType;
use App\Interfaces\Bookmarkable;
use App\Tips\Models\Tip;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $category
 * @property int $item_id
 * @property int $student_id
 * @property false|string $created_at
 * @property false|string $updated_at
 * @property Folder[] $folders
 * @property Student $student
 * @property Bookmarkable $item
 */
class SavedLearningItem extends Model
{
    public const CATEGORY_TIP = 'tip';
    public const CATEGORY_LAA = 'laa';
    public const CATEGORY_LAP = 'lap';


    // Disable using created_at and updated_at columns
    public $timestamps = false;

    // Override the table used for the User Model
    protected $table = 'saved_learning_items';

    // Override the primary key column
    protected $primaryKey = 'sli_id';

    // Default
    protected $fillable = [
        'category',
        'item_id',
        'student_id',
        'created_at',
        'updated_at',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function folders(): BelongsToMany
    {
        return $this->belongsToMany(Folder::class, 'sli_to_folder', 'sli_id', 'folder_id');
    }

    /**
     * @throws UnknownLearningItemType
     */
    public function item(): BelongsTo
    {
        if ($this->isTip()) {
            return $this->belongsTo(Tip::class);
        }

        if ($this->isActivity()) {
            if ($this->category === self::CATEGORY_LAA) {
                return $this->belongsTo(LearningActivityActing::class, 'item_id', 'laa_id');
            }
            if ($this->category === self::CATEGORY_LAP) {
                return $this->belongsTo(LearningActivityProducing::class, 'item_id', 'lap_id');
            }
        }

        throw new UnknownLearningItemType();
    }

    public function isTip(): bool
    {
        return $this->category === self::CATEGORY_TIP;
    }

    public function isActivity(): bool
    {
        return \in_array($this->category, [self::CATEGORY_LAA, self::CATEGORY_LAP], true);
    }
}
