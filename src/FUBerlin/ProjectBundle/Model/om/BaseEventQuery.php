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
use FUBerlin\ProjectBundle\Model\EventMember;
use FUBerlin\ProjectBundle\Model\EventPeer;
use FUBerlin\ProjectBundle\Model\EventPosition;
use FUBerlin\ProjectBundle\Model\EventQuery;
use FUBerlin\ProjectBundle\Model\User;

/**
 * @method EventQuery orderById($order = Criteria::ASC) Order by the id column
 * @method EventQuery orderByOwnerId($order = Criteria::ASC) Order by the owner_id column
 * @method EventQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method EventQuery orderByPlace($order = Criteria::ASC) Order by the place column
 * @method EventQuery orderByRequireReceipt($order = Criteria::ASC) Order by the require_receipt column
 * @method EventQuery orderByBilled($order = Criteria::ASC) Order by the billed column
 *
 * @method EventQuery groupById() Group by the id column
 * @method EventQuery groupByOwnerId() Group by the owner_id column
 * @method EventQuery groupByTitle() Group by the title column
 * @method EventQuery groupByPlace() Group by the place column
 * @method EventQuery groupByRequireReceipt() Group by the require_receipt column
 * @method EventQuery groupByBilled() Group by the billed column
 *
 * @method EventQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method EventQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method EventQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method EventQuery leftJoinOwnerUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the OwnerUser relation
 * @method EventQuery rightJoinOwnerUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OwnerUser relation
 * @method EventQuery innerJoinOwnerUser($relationAlias = null) Adds a INNER JOIN clause to the query using the OwnerUser relation
 *
 * @method EventQuery leftJoinEventMember($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventMember relation
 * @method EventQuery rightJoinEventMember($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventMember relation
 * @method EventQuery innerJoinEventMember($relationAlias = null) Adds a INNER JOIN clause to the query using the EventMember relation
 *
 * @method EventQuery leftJoinEventPosition($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventPosition relation
 * @method EventQuery rightJoinEventPosition($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventPosition relation
 * @method EventQuery innerJoinEventPosition($relationAlias = null) Adds a INNER JOIN clause to the query using the EventPosition relation
 *
 * @method Event findOne(PropelPDO $con = null) Return the first Event matching the query
 * @method Event findOneOrCreate(PropelPDO $con = null) Return the first Event matching the query, or a new Event object populated from the query conditions when no match is found
 *
 * @method Event findOneById(int $id) Return the first Event filtered by the id column
 * @method Event findOneByOwnerId(int $owner_id) Return the first Event filtered by the owner_id column
 * @method Event findOneByTitle(string $title) Return the first Event filtered by the title column
 * @method Event findOneByPlace(string $place) Return the first Event filtered by the place column
 * @method Event findOneByRequireReceipt(boolean $require_receipt) Return the first Event filtered by the require_receipt column
 * @method Event findOneByBilled(boolean $billed) Return the first Event filtered by the billed column
 *
 * @method array findById(int $id) Return Event objects filtered by the id column
 * @method array findByOwnerId(int $owner_id) Return Event objects filtered by the owner_id column
 * @method array findByTitle(string $title) Return Event objects filtered by the title column
 * @method array findByPlace(string $place) Return Event objects filtered by the place column
 * @method array findByRequireReceipt(boolean $require_receipt) Return Event objects filtered by the require_receipt column
 * @method array findByBilled(boolean $billed) Return Event objects filtered by the billed column
 */
abstract class BaseEventQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseEventQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'FUBerlin\\ProjectBundle\\Model\\Event', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new EventQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     EventQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return EventQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof EventQuery) {
            return $criteria;
        }
        $query = new EventQuery();
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
     * @return   Event|Event[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = EventPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(EventPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   Event A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `OWNER_ID`, `TITLE`, `PLACE`, `REQUIRE_RECEIPT`, `BILLED` FROM `event` WHERE `ID` = :p0';
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
            $obj = new Event();
            $obj->hydrate($row);
            EventPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Event|Event[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Event[]|mixed the list of results, formatted by the current formatter
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
     * @return EventQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EventPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return EventQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EventPeer::ID, $keys, Criteria::IN);
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
     * @return EventQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(EventPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the owner_id column
     *
     * Example usage:
     * <code>
     * $query->filterByOwnerId(1234); // WHERE owner_id = 1234
     * $query->filterByOwnerId(array(12, 34)); // WHERE owner_id IN (12, 34)
     * $query->filterByOwnerId(array('min' => 12)); // WHERE owner_id > 12
     * </code>
     *
     * @see       filterByOwnerUser()
     *
     * @param     mixed $ownerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventQuery The current query, for fluid interface
     */
    public function filterByOwnerId($ownerId = null, $comparison = null)
    {
        if (is_array($ownerId)) {
            $useMinMax = false;
            if (isset($ownerId['min'])) {
                $this->addUsingAlias(EventPeer::OWNER_ID, $ownerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ownerId['max'])) {
                $this->addUsingAlias(EventPeer::OWNER_ID, $ownerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventPeer::OWNER_ID, $ownerId, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EventPeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the place column
     *
     * Example usage:
     * <code>
     * $query->filterByPlace('fooValue');   // WHERE place = 'fooValue'
     * $query->filterByPlace('%fooValue%'); // WHERE place LIKE '%fooValue%'
     * </code>
     *
     * @param     string $place The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventQuery The current query, for fluid interface
     */
    public function filterByPlace($place = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($place)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $place)) {
                $place = str_replace('*', '%', $place);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EventPeer::PLACE, $place, $comparison);
    }

    /**
     * Filter the query on the require_receipt column
     *
     * Example usage:
     * <code>
     * $query->filterByRequireReceipt(true); // WHERE require_receipt = true
     * $query->filterByRequireReceipt('yes'); // WHERE require_receipt = true
     * </code>
     *
     * @param     boolean|string $requireReceipt The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventQuery The current query, for fluid interface
     */
    public function filterByRequireReceipt($requireReceipt = null, $comparison = null)
    {
        if (is_string($requireReceipt)) {
            $require_receipt = in_array(strtolower($requireReceipt), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(EventPeer::REQUIRE_RECEIPT, $requireReceipt, $comparison);
    }

    /**
     * Filter the query on the billed column
     *
     * Example usage:
     * <code>
     * $query->filterByBilled(true); // WHERE billed = true
     * $query->filterByBilled('yes'); // WHERE billed = true
     * </code>
     *
     * @param     boolean|string $billed The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EventQuery The current query, for fluid interface
     */
    public function filterByBilled($billed = null, $comparison = null)
    {
        if (is_string($billed)) {
            $billed = in_array(strtolower($billed), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(EventPeer::BILLED, $billed, $comparison);
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   EventQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByOwnerUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(EventPeer::OWNER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventPeer::OWNER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByOwnerUser() only accepts arguments of type User or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OwnerUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EventQuery The current query, for fluid interface
     */
    public function joinOwnerUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OwnerUser');

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
            $this->addJoinObject($join, 'OwnerUser');
        }

        return $this;
    }

    /**
     * Use the OwnerUser relation User object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \FUBerlin\ProjectBundle\Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useOwnerUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinOwnerUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OwnerUser', '\FUBerlin\ProjectBundle\Model\UserQuery');
    }

    /**
     * Filter the query by a related EventMember object
     *
     * @param   EventMember|PropelObjectCollection $eventMember  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   EventQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByEventMember($eventMember, $comparison = null)
    {
        if ($eventMember instanceof EventMember) {
            return $this
                ->addUsingAlias(EventPeer::ID, $eventMember->getEventId(), $comparison);
        } elseif ($eventMember instanceof PropelObjectCollection) {
            return $this
                ->useEventMemberQuery()
                ->filterByPrimaryKeys($eventMember->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEventMember() only accepts arguments of type EventMember or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventMember relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EventQuery The current query, for fluid interface
     */
    public function joinEventMember($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventMember');

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
            $this->addJoinObject($join, 'EventMember');
        }

        return $this;
    }

    /**
     * Use the EventMember relation EventMember object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \FUBerlin\ProjectBundle\Model\EventMemberQuery A secondary query class using the current class as primary query
     */
    public function useEventMemberQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventMember($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventMember', '\FUBerlin\ProjectBundle\Model\EventMemberQuery');
    }

    /**
     * Filter the query by a related EventPosition object
     *
     * @param   EventPosition|PropelObjectCollection $eventPosition  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   EventQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByEventPosition($eventPosition, $comparison = null)
    {
        if ($eventPosition instanceof EventPosition) {
            return $this
                ->addUsingAlias(EventPeer::ID, $eventPosition->getEventId(), $comparison);
        } elseif ($eventPosition instanceof PropelObjectCollection) {
            return $this
                ->useEventPositionQuery()
                ->filterByPrimaryKeys($eventPosition->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEventPosition() only accepts arguments of type EventPosition or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventPosition relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EventQuery The current query, for fluid interface
     */
    public function joinEventPosition($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventPosition');

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
            $this->addJoinObject($join, 'EventPosition');
        }

        return $this;
    }

    /**
     * Use the EventPosition relation EventPosition object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \FUBerlin\ProjectBundle\Model\EventPositionQuery A secondary query class using the current class as primary query
     */
    public function useEventPositionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinEventPosition($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventPosition', '\FUBerlin\ProjectBundle\Model\EventPositionQuery');
    }

    /**
     * Filter the query by a related User object
     * using the event_member table as cross reference
     *
     * @param   User $user the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   EventQuery The current query, for fluid interface
     */
    public function filterByMemberUser($user, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useEventMemberQuery()
            ->filterByMemberUser($user, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   Event $event Object to remove from the list of results
     *
     * @return EventQuery The current query, for fluid interface
     */
    public function prune($event = null)
    {
        if ($event) {
            $this->addUsingAlias(EventPeer::ID, $event->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
