<?php

namespace FUBerlin\ProjectBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use FUBerlin\ProjectBundle\Model\Event;
use FUBerlin\ProjectBundle\Model\EventBillingPosition;
use FUBerlin\ProjectBundle\Model\EventBillingPositionQuery;
use FUBerlin\ProjectBundle\Model\EventComment;
use FUBerlin\ProjectBundle\Model\EventCommentQuery;
use FUBerlin\ProjectBundle\Model\EventMember;
use FUBerlin\ProjectBundle\Model\EventMemberQuery;
use FUBerlin\ProjectBundle\Model\EventPeer;
use FUBerlin\ProjectBundle\Model\EventPosition;
use FUBerlin\ProjectBundle\Model\EventPositionQuery;
use FUBerlin\ProjectBundle\Model\EventQuery;
use FUBerlin\ProjectBundle\Model\User;
use FUBerlin\ProjectBundle\Model\UserQuery;

abstract class BaseEvent extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'FUBerlin\\ProjectBundle\\Model\\EventPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        EventPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinit loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the owner_id field.
     * @var        int
     */
    protected $owner_id;

    /**
     * The value for the title field.
     * @var        string
     */
    protected $title;

    /**
     * The value for the place field.
     * @var        string
     */
    protected $place;

    /**
     * The value for the require_receipt field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $require_receipt;

    /**
     * The value for the billed field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $billed;

    /**
     * @var        User
     */
    protected $aOwnerUser;

    /**
     * @var        PropelObjectCollection|EventMember[] Collection to store aggregation of EventMember objects.
     */
    protected $collEventMembers;
    protected $collEventMembersPartial;

    /**
     * @var        PropelObjectCollection|EventPosition[] Collection to store aggregation of EventPosition objects.
     */
    protected $collEventPositions;
    protected $collEventPositionsPartial;

    /**
     * @var        PropelObjectCollection|EventComment[] Collection to store aggregation of EventComment objects.
     */
    protected $collEventComments;
    protected $collEventCommentsPartial;

    /**
     * @var        PropelObjectCollection|EventBillingPosition[] Collection to store aggregation of EventBillingPosition objects.
     */
    protected $collEventBillingPositions;
    protected $collEventBillingPositionsPartial;

    /**
     * @var        PropelObjectCollection|User[] Collection to store aggregation of User objects.
     */
    protected $collMemberUsers;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $memberUsersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $eventMembersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $eventPositionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $eventCommentsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $eventBillingPositionsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->require_receipt = false;
        $this->billed = false;
    }

    /**
     * Initializes internal state of BaseEvent object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [owner_id] column value.
     *
     * @return int
     */
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * Get the [title] column value.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the [place] column value.
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Get the [require_receipt] column value.
     *
     * @return boolean
     */
    public function getRequireReceipt()
    {
        return $this->require_receipt;
    }

    /**
     * Get the [billed] column value.
     *
     * @return boolean
     */
    public function getBilled()
    {
        return $this->billed;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Event The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = EventPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [owner_id] column.
     *
     * @param int $v new value
     * @return Event The current object (for fluent API support)
     */
    public function setOwnerId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->owner_id !== $v) {
            $this->owner_id = $v;
            $this->modifiedColumns[] = EventPeer::OWNER_ID;
        }

        if ($this->aOwnerUser !== null && $this->aOwnerUser->getId() !== $v) {
            $this->aOwnerUser = null;
        }


        return $this;
    } // setOwnerId()

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return Event The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = EventPeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [place] column.
     *
     * @param string $v new value
     * @return Event The current object (for fluent API support)
     */
    public function setPlace($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->place !== $v) {
            $this->place = $v;
            $this->modifiedColumns[] = EventPeer::PLACE;
        }


        return $this;
    } // setPlace()

    /**
     * Sets the value of the [require_receipt] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Event The current object (for fluent API support)
     */
    public function setRequireReceipt($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->require_receipt !== $v) {
            $this->require_receipt = $v;
            $this->modifiedColumns[] = EventPeer::REQUIRE_RECEIPT;
        }


        return $this;
    } // setRequireReceipt()

    /**
     * Sets the value of the [billed] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Event The current object (for fluent API support)
     */
    public function setBilled($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->billed !== $v) {
            $this->billed = $v;
            $this->modifiedColumns[] = EventPeer::BILLED;
        }


        return $this;
    } // setBilled()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->require_receipt !== false) {
                return false;
            }

            if ($this->billed !== false) {
                return false;
            }

        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->owner_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->title = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->place = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->require_receipt = ($row[$startcol + 4] !== null) ? (boolean) $row[$startcol + 4] : null;
            $this->billed = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 6; // 6 = EventPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Event object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

        if ($this->aOwnerUser !== null && $this->owner_id !== $this->aOwnerUser->getId()) {
            $this->aOwnerUser = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(EventPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = EventPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aOwnerUser = null;
            $this->collEventMembers = null;

            $this->collEventPositions = null;

            $this->collEventComments = null;

            $this->collEventBillingPositions = null;

            $this->collMemberUsers = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(EventPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = EventQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(EventPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                EventPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aOwnerUser !== null) {
                if ($this->aOwnerUser->isModified() || $this->aOwnerUser->isNew()) {
                    $affectedRows += $this->aOwnerUser->save($con);
                }
                $this->setOwnerUser($this->aOwnerUser);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->memberUsersScheduledForDeletion !== null) {
                if (!$this->memberUsersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->memberUsersScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($remotePk, $pk);
                    }
                    EventMemberQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->memberUsersScheduledForDeletion = null;
                }

                foreach ($this->getMemberUsers() as $memberUser) {
                    if ($memberUser->isModified()) {
                        $memberUser->save($con);
                    }
                }
            }

            if ($this->eventMembersScheduledForDeletion !== null) {
                if (!$this->eventMembersScheduledForDeletion->isEmpty()) {
                    EventMemberQuery::create()
                        ->filterByPrimaryKeys($this->eventMembersScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventMembersScheduledForDeletion = null;
                }
            }

            if ($this->collEventMembers !== null) {
                foreach ($this->collEventMembers as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->eventPositionsScheduledForDeletion !== null) {
                if (!$this->eventPositionsScheduledForDeletion->isEmpty()) {
                    foreach ($this->eventPositionsScheduledForDeletion as $eventPosition) {
                        // need to save related object because we set the relation to null
                        $eventPosition->save($con);
                    }
                    $this->eventPositionsScheduledForDeletion = null;
                }
            }

            if ($this->collEventPositions !== null) {
                foreach ($this->collEventPositions as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->eventCommentsScheduledForDeletion !== null) {
                if (!$this->eventCommentsScheduledForDeletion->isEmpty()) {
                    foreach ($this->eventCommentsScheduledForDeletion as $eventComment) {
                        // need to save related object because we set the relation to null
                        $eventComment->save($con);
                    }
                    $this->eventCommentsScheduledForDeletion = null;
                }
            }

            if ($this->collEventComments !== null) {
                foreach ($this->collEventComments as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->eventBillingPositionsScheduledForDeletion !== null) {
                if (!$this->eventBillingPositionsScheduledForDeletion->isEmpty()) {
                    foreach ($this->eventBillingPositionsScheduledForDeletion as $eventBillingPosition) {
                        // need to save related object because we set the relation to null
                        $eventBillingPosition->save($con);
                    }
                    $this->eventBillingPositionsScheduledForDeletion = null;
                }
            }

            if ($this->collEventBillingPositions !== null) {
                foreach ($this->collEventBillingPositions as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = EventPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . EventPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EventPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`ID`';
        }
        if ($this->isColumnModified(EventPeer::OWNER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`OWNER_ID`';
        }
        if ($this->isColumnModified(EventPeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`TITLE`';
        }
        if ($this->isColumnModified(EventPeer::PLACE)) {
            $modifiedColumns[':p' . $index++]  = '`PLACE`';
        }
        if ($this->isColumnModified(EventPeer::REQUIRE_RECEIPT)) {
            $modifiedColumns[':p' . $index++]  = '`REQUIRE_RECEIPT`';
        }
        if ($this->isColumnModified(EventPeer::BILLED)) {
            $modifiedColumns[':p' . $index++]  = '`BILLED`';
        }

        $sql = sprintf(
            'INSERT INTO `event` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`ID`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`OWNER_ID`':
                        $stmt->bindValue($identifier, $this->owner_id, PDO::PARAM_INT);
                        break;
                    case '`TITLE`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`PLACE`':
                        $stmt->bindValue($identifier, $this->place, PDO::PARAM_STR);
                        break;
                    case '`REQUIRE_RECEIPT`':
                        $stmt->bindValue($identifier, (int) $this->require_receipt, PDO::PARAM_INT);
                        break;
                    case '`BILLED`':
                        $stmt->bindValue($identifier, (int) $this->billed, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        } else {
            $this->validationFailures = $res;

            return false;
        }
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggreagated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aOwnerUser !== null) {
                if (!$this->aOwnerUser->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aOwnerUser->getValidationFailures());
                }
            }


            if (($retval = EventPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collEventMembers !== null) {
                    foreach ($this->collEventMembers as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collEventPositions !== null) {
                    foreach ($this->collEventPositions as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collEventComments !== null) {
                    foreach ($this->collEventComments as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collEventBillingPositions !== null) {
                    foreach ($this->collEventBillingPositions as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = EventPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getOwnerId();
                break;
            case 2:
                return $this->getTitle();
                break;
            case 3:
                return $this->getPlace();
                break;
            case 4:
                return $this->getRequireReceipt();
                break;
            case 5:
                return $this->getBilled();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Event'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Event'][$this->getPrimaryKey()] = true;
        $keys = EventPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getOwnerId(),
            $keys[2] => $this->getTitle(),
            $keys[3] => $this->getPlace(),
            $keys[4] => $this->getRequireReceipt(),
            $keys[5] => $this->getBilled(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aOwnerUser) {
                $result['OwnerUser'] = $this->aOwnerUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collEventMembers) {
                $result['EventMembers'] = $this->collEventMembers->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEventPositions) {
                $result['EventPositions'] = $this->collEventPositions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEventComments) {
                $result['EventComments'] = $this->collEventComments->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEventBillingPositions) {
                $result['EventBillingPositions'] = $this->collEventBillingPositions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = EventPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setOwnerId($value);
                break;
            case 2:
                $this->setTitle($value);
                break;
            case 3:
                $this->setPlace($value);
                break;
            case 4:
                $this->setRequireReceipt($value);
                break;
            case 5:
                $this->setBilled($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = EventPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setOwnerId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setTitle($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setPlace($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setRequireReceipt($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setBilled($arr[$keys[5]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(EventPeer::DATABASE_NAME);

        if ($this->isColumnModified(EventPeer::ID)) $criteria->add(EventPeer::ID, $this->id);
        if ($this->isColumnModified(EventPeer::OWNER_ID)) $criteria->add(EventPeer::OWNER_ID, $this->owner_id);
        if ($this->isColumnModified(EventPeer::TITLE)) $criteria->add(EventPeer::TITLE, $this->title);
        if ($this->isColumnModified(EventPeer::PLACE)) $criteria->add(EventPeer::PLACE, $this->place);
        if ($this->isColumnModified(EventPeer::REQUIRE_RECEIPT)) $criteria->add(EventPeer::REQUIRE_RECEIPT, $this->require_receipt);
        if ($this->isColumnModified(EventPeer::BILLED)) $criteria->add(EventPeer::BILLED, $this->billed);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(EventPeer::DATABASE_NAME);
        $criteria->add(EventPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of Event (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setOwnerId($this->getOwnerId());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setPlace($this->getPlace());
        $copyObj->setRequireReceipt($this->getRequireReceipt());
        $copyObj->setBilled($this->getBilled());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getEventMembers() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventMember($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEventPositions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventPosition($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEventComments() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventComment($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEventBillingPositions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventBillingPosition($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return Event Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return EventPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new EventPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param             User $v
     * @return Event The current object (for fluent API support)
     * @throws PropelException
     */
    public function setOwnerUser(User $v = null)
    {
        if ($v === null) {
            $this->setOwnerId(NULL);
        } else {
            $this->setOwnerId($v->getId());
        }

        $this->aOwnerUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addEventOwner($this);
        }


        return $this;
    }


    /**
     * Get the associated User object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return User The associated User object.
     * @throws PropelException
     */
    public function getOwnerUser(PropelPDO $con = null)
    {
        if ($this->aOwnerUser === null && ($this->owner_id !== null)) {
            $this->aOwnerUser = UserQuery::create()->findPk($this->owner_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aOwnerUser->addEventOwners($this);
             */
        }

        return $this->aOwnerUser;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('EventMember' == $relationName) {
            $this->initEventMembers();
        }
        if ('EventPosition' == $relationName) {
            $this->initEventPositions();
        }
        if ('EventComment' == $relationName) {
            $this->initEventComments();
        }
        if ('EventBillingPosition' == $relationName) {
            $this->initEventBillingPositions();
        }
    }

    /**
     * Clears out the collEventMembers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEventMembers()
     */
    public function clearEventMembers()
    {
        $this->collEventMembers = null; // important to set this to null since that means it is uninitialized
        $this->collEventMembersPartial = null;
    }

    /**
     * reset is the collEventMembers collection loaded partially
     *
     * @return void
     */
    public function resetPartialEventMembers($v = true)
    {
        $this->collEventMembersPartial = $v;
    }

    /**
     * Initializes the collEventMembers collection.
     *
     * By default this just sets the collEventMembers collection to an empty array (like clearcollEventMembers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventMembers($overrideExisting = true)
    {
        if (null !== $this->collEventMembers && !$overrideExisting) {
            return;
        }
        $this->collEventMembers = new PropelObjectCollection();
        $this->collEventMembers->setModel('EventMember');
    }

    /**
     * Gets an array of EventMember objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Event is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|EventMember[] List of EventMember objects
     * @throws PropelException
     */
    public function getEventMembers($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEventMembersPartial && !$this->isNew();
        if (null === $this->collEventMembers || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventMembers) {
                // return empty collection
                $this->initEventMembers();
            } else {
                $collEventMembers = EventMemberQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEventMembersPartial && count($collEventMembers)) {
                      $this->initEventMembers(false);

                      foreach($collEventMembers as $obj) {
                        if (false == $this->collEventMembers->contains($obj)) {
                          $this->collEventMembers->append($obj);
                        }
                      }

                      $this->collEventMembersPartial = true;
                    }

                    return $collEventMembers;
                }

                if($partial && $this->collEventMembers) {
                    foreach($this->collEventMembers as $obj) {
                        if($obj->isNew()) {
                            $collEventMembers[] = $obj;
                        }
                    }
                }

                $this->collEventMembers = $collEventMembers;
                $this->collEventMembersPartial = false;
            }
        }

        return $this->collEventMembers;
    }

    /**
     * Sets a collection of EventMember objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $eventMembers A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setEventMembers(PropelCollection $eventMembers, PropelPDO $con = null)
    {
        $this->eventMembersScheduledForDeletion = $this->getEventMembers(new Criteria(), $con)->diff($eventMembers);

        foreach ($this->eventMembersScheduledForDeletion as $eventMemberRemoved) {
            $eventMemberRemoved->setEvent(null);
        }

        $this->collEventMembers = null;
        foreach ($eventMembers as $eventMember) {
            $this->addEventMember($eventMember);
        }

        $this->collEventMembers = $eventMembers;
        $this->collEventMembersPartial = false;
    }

    /**
     * Returns the number of related EventMember objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related EventMember objects.
     * @throws PropelException
     */
    public function countEventMembers(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEventMembersPartial && !$this->isNew();
        if (null === $this->collEventMembers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventMembers) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getEventMembers());
                }
                $query = EventMemberQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByEvent($this)
                    ->count($con);
            }
        } else {
            return count($this->collEventMembers);
        }
    }

    /**
     * Method called to associate a EventMember object to this object
     * through the EventMember foreign key attribute.
     *
     * @param    EventMember $l EventMember
     * @return Event The current object (for fluent API support)
     */
    public function addEventMember(EventMember $l)
    {
        if ($this->collEventMembers === null) {
            $this->initEventMembers();
            $this->collEventMembersPartial = true;
        }
        if (!$this->collEventMembers->contains($l)) { // only add it if the **same** object is not already associated
            $this->doAddEventMember($l);
        }

        return $this;
    }

    /**
     * @param	EventMember $eventMember The eventMember object to add.
     */
    protected function doAddEventMember($eventMember)
    {
        $this->collEventMembers[]= $eventMember;
        $eventMember->setEvent($this);
    }

    /**
     * @param	EventMember $eventMember The eventMember object to remove.
     */
    public function removeEventMember($eventMember)
    {
        if ($this->getEventMembers()->contains($eventMember)) {
            $this->collEventMembers->remove($this->collEventMembers->search($eventMember));
            if (null === $this->eventMembersScheduledForDeletion) {
                $this->eventMembersScheduledForDeletion = clone $this->collEventMembers;
                $this->eventMembersScheduledForDeletion->clear();
            }
            $this->eventMembersScheduledForDeletion[]= $eventMember;
            $eventMember->setEvent(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related EventMembers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EventMember[] List of EventMember objects
     */
    public function getEventMembersJoinMemberUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EventMemberQuery::create(null, $criteria);
        $query->joinWith('MemberUser', $join_behavior);

        return $this->getEventMembers($query, $con);
    }

    /**
     * Clears out the collEventPositions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEventPositions()
     */
    public function clearEventPositions()
    {
        $this->collEventPositions = null; // important to set this to null since that means it is uninitialized
        $this->collEventPositionsPartial = null;
    }

    /**
     * reset is the collEventPositions collection loaded partially
     *
     * @return void
     */
    public function resetPartialEventPositions($v = true)
    {
        $this->collEventPositionsPartial = $v;
    }

    /**
     * Initializes the collEventPositions collection.
     *
     * By default this just sets the collEventPositions collection to an empty array (like clearcollEventPositions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventPositions($overrideExisting = true)
    {
        if (null !== $this->collEventPositions && !$overrideExisting) {
            return;
        }
        $this->collEventPositions = new PropelObjectCollection();
        $this->collEventPositions->setModel('EventPosition');
    }

    /**
     * Gets an array of EventPosition objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Event is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|EventPosition[] List of EventPosition objects
     * @throws PropelException
     */
    public function getEventPositions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEventPositionsPartial && !$this->isNew();
        if (null === $this->collEventPositions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventPositions) {
                // return empty collection
                $this->initEventPositions();
            } else {
                $collEventPositions = EventPositionQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEventPositionsPartial && count($collEventPositions)) {
                      $this->initEventPositions(false);

                      foreach($collEventPositions as $obj) {
                        if (false == $this->collEventPositions->contains($obj)) {
                          $this->collEventPositions->append($obj);
                        }
                      }

                      $this->collEventPositionsPartial = true;
                    }

                    return $collEventPositions;
                }

                if($partial && $this->collEventPositions) {
                    foreach($this->collEventPositions as $obj) {
                        if($obj->isNew()) {
                            $collEventPositions[] = $obj;
                        }
                    }
                }

                $this->collEventPositions = $collEventPositions;
                $this->collEventPositionsPartial = false;
            }
        }

        return $this->collEventPositions;
    }

    /**
     * Sets a collection of EventPosition objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $eventPositions A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setEventPositions(PropelCollection $eventPositions, PropelPDO $con = null)
    {
        $this->eventPositionsScheduledForDeletion = $this->getEventPositions(new Criteria(), $con)->diff($eventPositions);

        foreach ($this->eventPositionsScheduledForDeletion as $eventPositionRemoved) {
            $eventPositionRemoved->setEvent(null);
        }

        $this->collEventPositions = null;
        foreach ($eventPositions as $eventPosition) {
            $this->addEventPosition($eventPosition);
        }

        $this->collEventPositions = $eventPositions;
        $this->collEventPositionsPartial = false;
    }

    /**
     * Returns the number of related EventPosition objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related EventPosition objects.
     * @throws PropelException
     */
    public function countEventPositions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEventPositionsPartial && !$this->isNew();
        if (null === $this->collEventPositions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventPositions) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getEventPositions());
                }
                $query = EventPositionQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByEvent($this)
                    ->count($con);
            }
        } else {
            return count($this->collEventPositions);
        }
    }

    /**
     * Method called to associate a EventPosition object to this object
     * through the EventPosition foreign key attribute.
     *
     * @param    EventPosition $l EventPosition
     * @return Event The current object (for fluent API support)
     */
    public function addEventPosition(EventPosition $l)
    {
        if ($this->collEventPositions === null) {
            $this->initEventPositions();
            $this->collEventPositionsPartial = true;
        }
        if (!$this->collEventPositions->contains($l)) { // only add it if the **same** object is not already associated
            $this->doAddEventPosition($l);
        }

        return $this;
    }

    /**
     * @param	EventPosition $eventPosition The eventPosition object to add.
     */
    protected function doAddEventPosition($eventPosition)
    {
        $this->collEventPositions[]= $eventPosition;
        $eventPosition->setEvent($this);
    }

    /**
     * @param	EventPosition $eventPosition The eventPosition object to remove.
     */
    public function removeEventPosition($eventPosition)
    {
        if ($this->getEventPositions()->contains($eventPosition)) {
            $this->collEventPositions->remove($this->collEventPositions->search($eventPosition));
            if (null === $this->eventPositionsScheduledForDeletion) {
                $this->eventPositionsScheduledForDeletion = clone $this->collEventPositions;
                $this->eventPositionsScheduledForDeletion->clear();
            }
            $this->eventPositionsScheduledForDeletion[]= $eventPosition;
            $eventPosition->setEvent(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related EventPositions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EventPosition[] List of EventPosition objects
     */
    public function getEventPositionsJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EventPositionQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getEventPositions($query, $con);
    }

    /**
     * Clears out the collEventComments collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEventComments()
     */
    public function clearEventComments()
    {
        $this->collEventComments = null; // important to set this to null since that means it is uninitialized
        $this->collEventCommentsPartial = null;
    }

    /**
     * reset is the collEventComments collection loaded partially
     *
     * @return void
     */
    public function resetPartialEventComments($v = true)
    {
        $this->collEventCommentsPartial = $v;
    }

    /**
     * Initializes the collEventComments collection.
     *
     * By default this just sets the collEventComments collection to an empty array (like clearcollEventComments());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventComments($overrideExisting = true)
    {
        if (null !== $this->collEventComments && !$overrideExisting) {
            return;
        }
        $this->collEventComments = new PropelObjectCollection();
        $this->collEventComments->setModel('EventComment');
    }

    /**
     * Gets an array of EventComment objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Event is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|EventComment[] List of EventComment objects
     * @throws PropelException
     */
    public function getEventComments($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEventCommentsPartial && !$this->isNew();
        if (null === $this->collEventComments || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventComments) {
                // return empty collection
                $this->initEventComments();
            } else {
                $collEventComments = EventCommentQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEventCommentsPartial && count($collEventComments)) {
                      $this->initEventComments(false);

                      foreach($collEventComments as $obj) {
                        if (false == $this->collEventComments->contains($obj)) {
                          $this->collEventComments->append($obj);
                        }
                      }

                      $this->collEventCommentsPartial = true;
                    }

                    return $collEventComments;
                }

                if($partial && $this->collEventComments) {
                    foreach($this->collEventComments as $obj) {
                        if($obj->isNew()) {
                            $collEventComments[] = $obj;
                        }
                    }
                }

                $this->collEventComments = $collEventComments;
                $this->collEventCommentsPartial = false;
            }
        }

        return $this->collEventComments;
    }

    /**
     * Sets a collection of EventComment objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $eventComments A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setEventComments(PropelCollection $eventComments, PropelPDO $con = null)
    {
        $this->eventCommentsScheduledForDeletion = $this->getEventComments(new Criteria(), $con)->diff($eventComments);

        foreach ($this->eventCommentsScheduledForDeletion as $eventCommentRemoved) {
            $eventCommentRemoved->setEvent(null);
        }

        $this->collEventComments = null;
        foreach ($eventComments as $eventComment) {
            $this->addEventComment($eventComment);
        }

        $this->collEventComments = $eventComments;
        $this->collEventCommentsPartial = false;
    }

    /**
     * Returns the number of related EventComment objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related EventComment objects.
     * @throws PropelException
     */
    public function countEventComments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEventCommentsPartial && !$this->isNew();
        if (null === $this->collEventComments || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventComments) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getEventComments());
                }
                $query = EventCommentQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByEvent($this)
                    ->count($con);
            }
        } else {
            return count($this->collEventComments);
        }
    }

    /**
     * Method called to associate a EventComment object to this object
     * through the EventComment foreign key attribute.
     *
     * @param    EventComment $l EventComment
     * @return Event The current object (for fluent API support)
     */
    public function addEventComment(EventComment $l)
    {
        if ($this->collEventComments === null) {
            $this->initEventComments();
            $this->collEventCommentsPartial = true;
        }
        if (!$this->collEventComments->contains($l)) { // only add it if the **same** object is not already associated
            $this->doAddEventComment($l);
        }

        return $this;
    }

    /**
     * @param	EventComment $eventComment The eventComment object to add.
     */
    protected function doAddEventComment($eventComment)
    {
        $this->collEventComments[]= $eventComment;
        $eventComment->setEvent($this);
    }

    /**
     * @param	EventComment $eventComment The eventComment object to remove.
     */
    public function removeEventComment($eventComment)
    {
        if ($this->getEventComments()->contains($eventComment)) {
            $this->collEventComments->remove($this->collEventComments->search($eventComment));
            if (null === $this->eventCommentsScheduledForDeletion) {
                $this->eventCommentsScheduledForDeletion = clone $this->collEventComments;
                $this->eventCommentsScheduledForDeletion->clear();
            }
            $this->eventCommentsScheduledForDeletion[]= $eventComment;
            $eventComment->setEvent(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related EventComments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EventComment[] List of EventComment objects
     */
    public function getEventCommentsJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EventCommentQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getEventComments($query, $con);
    }

    /**
     * Clears out the collEventBillingPositions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEventBillingPositions()
     */
    public function clearEventBillingPositions()
    {
        $this->collEventBillingPositions = null; // important to set this to null since that means it is uninitialized
        $this->collEventBillingPositionsPartial = null;
    }

    /**
     * reset is the collEventBillingPositions collection loaded partially
     *
     * @return void
     */
    public function resetPartialEventBillingPositions($v = true)
    {
        $this->collEventBillingPositionsPartial = $v;
    }

    /**
     * Initializes the collEventBillingPositions collection.
     *
     * By default this just sets the collEventBillingPositions collection to an empty array (like clearcollEventBillingPositions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventBillingPositions($overrideExisting = true)
    {
        if (null !== $this->collEventBillingPositions && !$overrideExisting) {
            return;
        }
        $this->collEventBillingPositions = new PropelObjectCollection();
        $this->collEventBillingPositions->setModel('EventBillingPosition');
    }

    /**
     * Gets an array of EventBillingPosition objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Event is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|EventBillingPosition[] List of EventBillingPosition objects
     * @throws PropelException
     */
    public function getEventBillingPositions($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEventBillingPositionsPartial && !$this->isNew();
        if (null === $this->collEventBillingPositions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventBillingPositions) {
                // return empty collection
                $this->initEventBillingPositions();
            } else {
                $collEventBillingPositions = EventBillingPositionQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEventBillingPositionsPartial && count($collEventBillingPositions)) {
                      $this->initEventBillingPositions(false);

                      foreach($collEventBillingPositions as $obj) {
                        if (false == $this->collEventBillingPositions->contains($obj)) {
                          $this->collEventBillingPositions->append($obj);
                        }
                      }

                      $this->collEventBillingPositionsPartial = true;
                    }

                    return $collEventBillingPositions;
                }

                if($partial && $this->collEventBillingPositions) {
                    foreach($this->collEventBillingPositions as $obj) {
                        if($obj->isNew()) {
                            $collEventBillingPositions[] = $obj;
                        }
                    }
                }

                $this->collEventBillingPositions = $collEventBillingPositions;
                $this->collEventBillingPositionsPartial = false;
            }
        }

        return $this->collEventBillingPositions;
    }

    /**
     * Sets a collection of EventBillingPosition objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $eventBillingPositions A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setEventBillingPositions(PropelCollection $eventBillingPositions, PropelPDO $con = null)
    {
        $this->eventBillingPositionsScheduledForDeletion = $this->getEventBillingPositions(new Criteria(), $con)->diff($eventBillingPositions);

        foreach ($this->eventBillingPositionsScheduledForDeletion as $eventBillingPositionRemoved) {
            $eventBillingPositionRemoved->setEvent(null);
        }

        $this->collEventBillingPositions = null;
        foreach ($eventBillingPositions as $eventBillingPosition) {
            $this->addEventBillingPosition($eventBillingPosition);
        }

        $this->collEventBillingPositions = $eventBillingPositions;
        $this->collEventBillingPositionsPartial = false;
    }

    /**
     * Returns the number of related EventBillingPosition objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related EventBillingPosition objects.
     * @throws PropelException
     */
    public function countEventBillingPositions(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEventBillingPositionsPartial && !$this->isNew();
        if (null === $this->collEventBillingPositions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventBillingPositions) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getEventBillingPositions());
                }
                $query = EventBillingPositionQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByEvent($this)
                    ->count($con);
            }
        } else {
            return count($this->collEventBillingPositions);
        }
    }

    /**
     * Method called to associate a EventBillingPosition object to this object
     * through the EventBillingPosition foreign key attribute.
     *
     * @param    EventBillingPosition $l EventBillingPosition
     * @return Event The current object (for fluent API support)
     */
    public function addEventBillingPosition(EventBillingPosition $l)
    {
        if ($this->collEventBillingPositions === null) {
            $this->initEventBillingPositions();
            $this->collEventBillingPositionsPartial = true;
        }
        if (!$this->collEventBillingPositions->contains($l)) { // only add it if the **same** object is not already associated
            $this->doAddEventBillingPosition($l);
        }

        return $this;
    }

    /**
     * @param	EventBillingPosition $eventBillingPosition The eventBillingPosition object to add.
     */
    protected function doAddEventBillingPosition($eventBillingPosition)
    {
        $this->collEventBillingPositions[]= $eventBillingPosition;
        $eventBillingPosition->setEvent($this);
    }

    /**
     * @param	EventBillingPosition $eventBillingPosition The eventBillingPosition object to remove.
     */
    public function removeEventBillingPosition($eventBillingPosition)
    {
        if ($this->getEventBillingPositions()->contains($eventBillingPosition)) {
            $this->collEventBillingPositions->remove($this->collEventBillingPositions->search($eventBillingPosition));
            if (null === $this->eventBillingPositionsScheduledForDeletion) {
                $this->eventBillingPositionsScheduledForDeletion = clone $this->collEventBillingPositions;
                $this->eventBillingPositionsScheduledForDeletion->clear();
            }
            $this->eventBillingPositionsScheduledForDeletion[]= $eventBillingPosition;
            $eventBillingPosition->setEvent(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related EventBillingPositions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EventBillingPosition[] List of EventBillingPosition objects
     */
    public function getEventBillingPositionsJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EventBillingPositionQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getEventBillingPositions($query, $con);
    }

    /**
     * Clears out the collMemberUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMemberUsers()
     */
    public function clearMemberUsers()
    {
        $this->collMemberUsers = null; // important to set this to null since that means it is uninitialized
        $this->collMemberUsersPartial = null;
    }

    /**
     * Initializes the collMemberUsers collection.
     *
     * By default this just sets the collMemberUsers collection to an empty collection (like clearMemberUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initMemberUsers()
    {
        $this->collMemberUsers = new PropelObjectCollection();
        $this->collMemberUsers->setModel('User');
    }

    /**
     * Gets a collection of User objects related by a many-to-many relationship
     * to the current object by way of the event_member cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Event is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|User[] List of User objects
     */
    public function getMemberUsers($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collMemberUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collMemberUsers) {
                // return empty collection
                $this->initMemberUsers();
            } else {
                $collMemberUsers = UserQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collMemberUsers;
                }
                $this->collMemberUsers = $collMemberUsers;
            }
        }

        return $this->collMemberUsers;
    }

    /**
     * Sets a collection of User objects related by a many-to-many relationship
     * to the current object by way of the event_member cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $memberUsers A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setMemberUsers(PropelCollection $memberUsers, PropelPDO $con = null)
    {
        $this->clearMemberUsers();
        $currentMemberUsers = $this->getMemberUsers();

        $this->memberUsersScheduledForDeletion = $currentMemberUsers->diff($memberUsers);

        foreach ($memberUsers as $memberUser) {
            if (!$currentMemberUsers->contains($memberUser)) {
                $this->doAddMemberUser($memberUser);
            }
        }

        $this->collMemberUsers = $memberUsers;
    }

    /**
     * Gets the number of User objects related by a many-to-many relationship
     * to the current object by way of the event_member cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related User objects
     */
    public function countMemberUsers($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collMemberUsers || null !== $criteria) {
            if ($this->isNew() && null === $this->collMemberUsers) {
                return 0;
            } else {
                $query = UserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByEvent($this)
                    ->count($con);
            }
        } else {
            return count($this->collMemberUsers);
        }
    }

    /**
     * Associate a User object to this object
     * through the event_member cross reference table.
     *
     * @param  User $user The EventMember object to relate
     * @return void
     */
    public function addMemberUser(User $user)
    {
        if ($this->collMemberUsers === null) {
            $this->initMemberUsers();
        }
        if (!$this->collMemberUsers->contains($user)) { // only add it if the **same** object is not already associated
            $this->doAddMemberUser($user);

            $this->collMemberUsers[]= $user;
        }
    }

    /**
     * @param	MemberUser $memberUser The memberUser object to add.
     */
    protected function doAddMemberUser($memberUser)
    {
        $eventMember = new EventMember();
        $eventMember->setMemberUser($memberUser);
        $this->addEventMember($eventMember);
    }

    /**
     * Remove a User object to this object
     * through the event_member cross reference table.
     *
     * @param User $user The EventMember object to relate
     * @return void
     */
    public function removeMemberUser(User $user)
    {
        if ($this->getMemberUsers()->contains($user)) {
            $this->collMemberUsers->remove($this->collMemberUsers->search($user));
            if (null === $this->memberUsersScheduledForDeletion) {
                $this->memberUsersScheduledForDeletion = clone $this->collMemberUsers;
                $this->memberUsersScheduledForDeletion->clear();
            }
            $this->memberUsersScheduledForDeletion[]= $user;
        }
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->owner_id = null;
        $this->title = null;
        $this->place = null;
        $this->require_receipt = null;
        $this->billed = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volumne/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collEventMembers) {
                foreach ($this->collEventMembers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEventPositions) {
                foreach ($this->collEventPositions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEventComments) {
                foreach ($this->collEventComments as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEventBillingPositions) {
                foreach ($this->collEventBillingPositions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMemberUsers) {
                foreach ($this->collMemberUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collEventMembers instanceof PropelCollection) {
            $this->collEventMembers->clearIterator();
        }
        $this->collEventMembers = null;
        if ($this->collEventPositions instanceof PropelCollection) {
            $this->collEventPositions->clearIterator();
        }
        $this->collEventPositions = null;
        if ($this->collEventComments instanceof PropelCollection) {
            $this->collEventComments->clearIterator();
        }
        $this->collEventComments = null;
        if ($this->collEventBillingPositions instanceof PropelCollection) {
            $this->collEventBillingPositions->clearIterator();
        }
        $this->collEventBillingPositions = null;
        if ($this->collMemberUsers instanceof PropelCollection) {
            $this->collMemberUsers->clearIterator();
        }
        $this->collMemberUsers = null;
        $this->aOwnerUser = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(EventPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

}
