<?php


namespace Core\Model;

use MongoDB\Laravel\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;


class CustomBelongsToMany extends BelongsToMany {
    /**
     * @inheritdoc
     */
    public function attach($id, array $attributes = [], $touch = true)
    {
        if ($id instanceof Model) {
            $model = $id;

            $id = $model->{$this->relatedKey};

            // Attach the new parent id to the related model.
            $model->push($this->foreignPivotKey, $this->parent->{$this->parentKey}, true);
        } else {
            if ($id instanceof Collection) {
                $id = $id->modelKeys();
            }

            $query = $this->newRelatedQuery();

            $query->whereIn($this->related->getKeyName(), (array) $id);

            // Attach the new parent id to the related model.
            $query->push($this->foreignPivotKey, $this->parent->{$this->parentKey}, true);
        }

        // Attach the new ids to the parent model.
        $this->parent->push($this->getRelatedKey(), (array) $id, true);

        if ($touch) {
            $this->touchIfTouching();
        }
    }

    /**
     * @inheritdoc
     */
    public function detach($ids = [], $touch = true)
    {
        if ($ids instanceof Model) {
            $ids = (array) $ids->{$this->privateKey};
        }

        $query = $this->newRelatedQuery();

        // If associated IDs were passed to the method we will only delete those
        // associations, otherwise all of the association ties will be broken.
        // We'll return the numbers of affected rows when we do the deletes.
        $ids = (array) $ids;

        // Detach all ids from the parent model.
        $this->parent->pull($this->getRelatedKey(), $ids);

        // Prepare the query to select all related objects.
        if (count($ids) > 0) {
            $query->whereIn($this->relatedKey, $ids);
        }

        // Remove the relation to the parent.
        $query->pull($this->foreignPivotKey, $this->parent->{$this->parentKey});

        if ($touch) {
            $this->touchIfTouching();
        }

        return count($ids);
    }

    /**
     * Set the where clause for the relation query.
     * @return $this
     */
    protected function setWhere()
    {
        $foreign = $this->getForeignKey();

        $this->query->where($foreign, '=', $this->parent->{$this->parentKey});

        return $this;
    }
}
