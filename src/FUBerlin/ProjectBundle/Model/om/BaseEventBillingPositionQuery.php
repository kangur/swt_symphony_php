<?php

namespace FUBerlin\ProjectBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use FUBerlin\ProjectBundle\Model\Event;
use FUBerlin\ProjectBundle\Model\EventBillingPosition;
use FUBerlin\ProjectBundle\Model\EventBillingPositionPeer;
use FUBerlin\ProjectBundle\Model\EventBillingPositionQuery;
use FUBerlin\ProjectBundle\Model\User;

/**
 * @method EventBillingPositionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method EventBillingPositionQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method EventBillingPositionQuery orderByEventId($order = Criteria::ASC) Order by the event_id column
 * @method EventBillingPositionQuery orderByAmount($order = Criteria::ASC) Order by the amount column
 * @method EventBillingPositionQuery orderByPaid($order = Criteria::ASC) Order by the paid column
 *
 * @method EventBillingPositionQuery groupById() Group by the id column
 * @method EventBillingPositionQuery groupByUserId() Group by the user_id column
 * @method EventBillingPositionQuery groupByEventId() Group by the event_id column
 * @method EventBillingPositionQuery groupByAmount() Group by the amount column
 * @method EventBillingPositionQuery groupByPaid() Group by the paid column
 *
 * @method EventBillingPositionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method EventBillingPositionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method EventBillingPositionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method EventBillingPositionQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method EventBillingPositionQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method EventBillingPositionQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method EventBillingPositionQuery leftJoinEvent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Event relation
 * @method EventBillingPositionQuery rightJoinEvent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Event relation
 * @method EventBillingPositionQuery innerJoinEvent($relationAlias = null) Adds a INNER JOIN clause to the query using the Event relation
 *
 * @method EventBillingPosition findOne(PropelPDO $con = null) Return the first EventBillingPosition matching the query
 * @method EventBillingPosition findOneOrCreate(PropelPDO $con = null) Return the first EventBillingPosition matching the query, or a new EventBillingPosition object populated from the query conditions when no match is found
 *
 * @method EventBillingPosition findOneById(int $id) Return the first EventBillingPosition filtered by the id column
 * @method EventBillingPosition findOneByUserId(int $user_id) Return the first EventBillingPosition filtered by the user_id column
 * @method EventBillingPosition findOneByEventId(int $event_id) Return the first EventBillingPosition filtered by the event_id column
 * @method EventBillingPosition findOneByAmount(string $amount) Return the first EventBillingPosition filtered by the amount column
 * @method EventBillingPosition findOneByPaid(boolean $paid) Return the first EventBillingPosition filtered by the paid column
 *
 * @method array findById(int $id) Return EventBillingPosition objects filtered by the id column
 * @method array findByUserId(int $user_id) Return EventBillingPosition objects filtered by the user_id column
 * @method array findByEventId(int $event_id) Return EventBillingPosition objects filtered by the event_id column
 * @method array findByAmount(string $amount) Return EventBillingPosition objects filtered by the amount column
 * @method array findByPaid(boolean $paid) Return EventBillingPosition objects filtered by the paid column
 */
abstract class BaseEventBillingPositionQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseEventBillingPositionQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'FUBerlin\\ProjectBundle\\Model\\EventBillingPosition', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new EventBillingPositionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     EventBillingPositionQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return EventBillingPositionQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof EventBillingPositionQuery) {
            return $criteria;
        }
        $query = new EventBillingPositionQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   EventBillingPosition|EventBillingPosition[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = EventBillingPositionPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(EventBillingPositionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return   EventBillingPosition A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `USER_ID`, `EVENT_ID`, `AMOUNT`, `PAID` FROM `event_billing_position` WHERE `ID` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new EventBillingPosition();
            $obj->hydrate($row);
            EventBillingPositionPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return EventBillingPosition|EventBillingPosition[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|EventBillingPosition[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return EventBillingPositionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EventBillingPositionPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return EventBillingPositionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EventBillingPositionPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventBillingPositionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(EventBillingPositionPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventBillingPositionQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(EventBillingPositionPeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(EventBillingPositionPeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventBillingPositionPeer::USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the event_id column
     *
     * Example usage:
     * <code>
     * $query->filterByEventId(1234); // WHERE event_id = 1234
     * $query->filterByEventId(array(12, 34)); // WHERE event_id IN (12, 34)
     * $query->filterByEventId(array('min' => 12)); // WHERE event_id > 12
     * </code>
     *
     * @see       filterByEvent()
     *
     * @param     mixed $eventId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventBillingPositionQuery The current query, for fluid interface
     */
    public function filterByEventId($eventId = null, $comparison = null)
    {
        if (is_array($eventId)) {
            $useMinMax = false;
            if (isset($eventId['min'])) {
                $this->addUsingAlias(EventBillingPositionPeer::EVENT_ID, $eventId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventId['max'])) {
                $this->addUsingAlias(EventBillingPositionPeer::EVENT_ID, $eventId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventBillingPositionPeer::EVENT_ID, $eventId, $comparison);
    }

    /**
     * Filter the query on the amount column
     *
     * Example usage:
     * <code>
     * $query->filterByAmount(1234); // WHERE amount = 1234
     * $query->filterByAmount(array(12, 34)); // WHERE amount IN (12, 34)
     * $query->filterByAmount(array('min' => 12)); // WHERE amount > 12
     * </code>
     *
     * @param     mixed $amount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventBillingPositionQuery The current query, for fluid interface
     */
    public function filterByAmount($amount = null, $comparison = null)
    {
        if (is_array($amount)) {
            $useMinMax = false;
            if (isset($amount['min'])) {
                $this->addUsingAlias(EventBillingPositionPeer::AMOUNT, $amount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amount['max'])) {
                $this->addUsingAlias(EventBillingPositionPeer::AMOUNT, $amount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventBillingPositionPeer::AMOUNT, $amount, $comparison);
    }

    /**
     * Filter the query on the paid column
     *
     * Example usage:
     * <code>
     * $query->filterByPaid(true); // WHERE paid = true
     * $query->filterByPaid('yes'); // WHERE paid = true
     * </code>
     *
     * @param     boolean|string $paid The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventBillingPositionQuery The current query, for fluid interface
     */
    public function filterByPaid($paid = null, $comparison = null)
    {
        if (is_string($paid)) {
            $paid = in_array(strtolower($paid), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(EventBillingPositionPeer::PAID, $paid, $comparison);
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   EventBillingPositionQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(EventBillingPositionPeer::USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventBillingPositionPeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type User or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EventBillingPositionQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \FUBerlin\ProjectBundle\Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\FUBerlin\ProjectBundle\Model\UserQuery');
    }

    /**
     * Filter the query by a related Event object
     *
     * @param   Event|PropelObjectCollection $event The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   EventBillingPositionQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByEvent($event, $comparison = null)
    {
        if ($event instanceof Event) {
            return $this
                ->addUsingAlias(EventBillingPositionPeer::EVENT_ID, $event->getId(), $comparison);
        } elseif ($event instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventBillingPositionPeer::EVENT_ID, $event->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByEvent() only accepts arguments of type Event or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Event relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EventBillingPositionQuery The current query, for fluid interface
     */
    public function joinEvent($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Event');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Event');
        }

        return $this;
    }

    /**
     * Use the Event relation Event object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \FUBerlin\ProjectBundle\Model\EventQuery A secondary query class using the current class as primary query
     */
    public function useEventQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinEvent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Event', '\FUBerlin\ProjectBundle\Model\EventQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   EventBillingPosition $eventBillingPosition Object to remove from the list of results
     *
     * @return EventBillingPositionQuery The current query, for fluid interface
     */
    public function prune($eventBillingPosition = null)
    {
        if ($eventBillingPosition) {
            $this->addUsingAlias(EventBillingPositionPeer::ID, $eventBillingPosition->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
