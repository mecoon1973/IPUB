<?php

namespace Core\Traits;

use Core\Model\CustomBelongsToMany;

trait CustomHybridRelations {
    /**
     * Define a many-to-many relationship.
     * @param string $related
     * @param string $collection
     * @param string $foreignKey
     * @param string $otherKey
     * @param string $parentKey
     * @param string $relatedKey
     * @param string $relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function customBelongsToMany(
        $related,
        $collection = null,
        $foreignKey = null,
        $otherKey = null,
        $parentKey = null,
        $relatedKey = null,
        $relation = null
    ) {
        // If no relationship name was passed, we will pull backtraces to get the
        // name of the calling function. We will use that function name as the
        // title of this relation since that is a great convention to apply.
        if ($relation === null) {
            $relation = $this->guessBelongsToManyRelation();
        }

        // Check if it is a relation with an original model.
        if (! is_subclass_of($related, \MongoDB\Laravel\Eloquent\Model::class)) {
            return parent::belongsToMany(
                $related,
                $collection,
                $foreignKey,
                $otherKey,
                $parentKey,
                $relatedKey,
                $relation
            );
        }

        // First, we'll need to determine the foreign key and "other key" for the
        // relationship. Once we have determined the keys we'll make the query
        // instances as well as the relationship instances we need for this.
        $foreignKey = $foreignKey ?: $this->getForeignKey().'s';

        $instance = new $related;

        $otherKey = $otherKey ?: $instance->getForeignKey().'s';

        // If no table name was provided, we can guess it by concatenating the two
        // models using underscores in alphabetical order. The two model names
        // are transformed to snake case from their default CamelCase also.
        if ($collection === null) {
            $collection = $instance->getTable();
        }

        // Now we're ready to create a new query builder for the related model and
        // the relationship instances for the relation. The relations will set
        // appropriate query constraint and entirely manages the hydrations.
        $query = $instance->newQuery();

        return new CustomBelongsToMany(
            $query,
            $this,
            $collection,
            $foreignKey,
            $otherKey,
            $parentKey ?: $this->getKeyName(),
            $relatedKey ?: $instance->getKeyName(),
            $relation
        );
    }
}
