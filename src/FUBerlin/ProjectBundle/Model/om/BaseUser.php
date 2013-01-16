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
use FUBerlin\ProjectBundle\Model\EventPosition;
use FUBerlin\ProjectBundle\Model\EventPositionQuery;
use FUBerlin\ProjectBundle\Model\EventQuery;
use FUBerlin\ProjectBundle\Model\User;
use FUBerlin\ProjectBundle\Model\UserPeer;
use FUBerlin\ProjectBundle\Model\UserQuery;

abstract class BaseUser extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'FUBerlin\\ProjectBundle\\Model\\UserPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        UserPeer
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
     * The value for the username field.
     * @var        string
     */
    protected $username;

    /**
     * The value for the first_name field.
     * @var        string
     */
    protected $first_name;

    /**
     * The value for the last_name field.
     * @var        string
     */
    protected $last_name;

    /**
     * The value for the email field.
     * @var        string
     */
    protected $email;

    /**
     * The value for the password field.
     * @var        string
     */
    protected $password;

    /**
     * @var        PropelObjectCollection|Event[] Collection to store aggregation of Event objects.
     */
    protected $collEventOwners;
    protected $collEventOwnersPartial;

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
     * @var        PropelObjectCollection|Event[] Collection to store aggregation of Event objects.
     */
    protected $collEvents;

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
    protected $eventsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $eventOwnersScheduledForDeletion = null;

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
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [username] column value.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the [first_name] column value.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Get the [last_name] column value.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the [password] column value.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return User The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = UserPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [username] column.
     *
     * @param string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->username !== $v) {
            $this->username = $v;
            $this->modifiedColumns[] = UserPeer::USERNAME;
        }


        return $this;
    } // setUsername()

    /**
     * Set the value of [first_name] column.
     *
     * @param string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setFirstName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->first_name !== $v) {
            $this->first_name = $v;
            $this->modifiedColumns[] = UserPeer::FIRST_NAME;
        }


        return $this;
    } // setFirstName()

    /**
     * Set the value of [last_name] column.
     *
     * @param string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setLastName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->last_name !== $v) {
            $this->last_name = $v;
            $this->modifiedColumns[] = UserPeer::LAST_NAME;
        }


        return $this;
    } // setLastName()

    /**
     * Set the value of [email] column.
     *
     * @param string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[] = UserPeer::EMAIL;
        }


        return $this;
    } // setEmail()

    /**
     * Set the value of [password] column.
     *
     * @param string $v new value
     * @return User The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[] = UserPeer::PASSWORD;
        }


        return $this;
    } // setPassword()

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
            $this->username = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->first_name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->last_name = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->email = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->password = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 6; // 6 = UserPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating User object", $e);
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
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = UserPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collEventOwners = null;

            $this->collEventMembers = null;

            $this->collEventPositions = null;

            $this->collEventComments = null;

            $this->collEventBillingPositions = null;

            $this->collEvents = null;
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
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = UserQuery::create()
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
            $con = Propel::getConnection(UserPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                UserPeer::addInstanceToPool($this);
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

            if ($this->eventsScheduledForDeletion !== null) {
                if (!$this->eventsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    $pk = $this->getPrimaryKey();
                    foreach ($this->eventsScheduledForDeletion->getPrimaryKeys(false) as $remotePk) {
                        $pks[] = array($pk, $remotePk);
                    }
                    EventMemberQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);
                    $this->eventsScheduledForDeletion = null;
                }

                foreach ($this->getEvents() as $event) {
                    if ($event->isModified()) {
                        $event->save($con);
                    }
                }
            }

            if ($this->eventOwnersScheduledForDeletion !== null) {
                if (!$this->eventOwnersScheduledForDeletion->isEmpty()) {
                    foreach ($this->eventOwnersScheduledForDeletion as $eventOwner) {
                        // need to save related object because we set the relation to null
                        $eventOwner->save($con);
                    }
                    $this->eventOwnersScheduledForDeletion = null;
                }
            }

            if ($this->collEventOwners !== null) {
                foreach ($this->collEventOwners as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
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

        $this->modifiedColumns[] = UserPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`ID`';
        }
        if ($this->isColumnModified(UserPeer::USERNAME)) {
            $modifiedColumns[':p' . $index++]  = '`USERNAME`';
        }
        if ($this->isColumnModified(UserPeer::FIRST_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`FIRST_NAME`';
        }
        if ($this->isColumnModified(UserPeer::LAST_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`LAST_NAME`';
        }
        if ($this->isColumnModified(UserPeer::EMAIL)) {
            $modifiedColumns[':p' . $index++]  = '`EMAIL`';
        }
        if ($this->isColumnModified(UserPeer::PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = '`PASSWORD`';
        }

        $sql = sprintf(
            'INSERT INTO `user` (%s) VALUES (%s)',
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
                    case '`USERNAME`':
                        $stmt->bindValue($identifier, $this->username, PDO::PARAM_STR);
                        break;
                    case '`FIRST_NAME`':
                        $stmt->bindValue($identifier, $this->first_name, PDO::PARAM_STR);
                        break;
                    case '`LAST_NAME`':
                        $stmt->bindValue($identifier, $this->last_name, PDO::PARAM_STR);
                        break;
                    case '`EMAIL`':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case '`PASSWORD`':
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
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


            if (($retval = UserPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collEventOwners !== null) {
                    foreach ($this->collEventOwners as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
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
        $pos = UserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getUsername();
                break;
            case 2:
                return $this->getFirstName();
                break;
            case 3:
                return $this->getLastName();
                break;
            case 4:
                return $this->getEmail();
                break;
            case 5:
                return $this->getPassword();
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
        if (isset($alreadyDumpedObjects['User'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->getPrimaryKey()] = true;
        $keys = UserPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUsername(),
            $keys[2] => $this->getFirstName(),
            $keys[3] => $this->getLastName(),
            $keys[4] => $this->getEmail(),
            $keys[5] => $this->getPassword(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->collEventOwners) {
                $result['EventOwners'] = $this->collEventOwners->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = UserPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setUsername($value);
                break;
            case 2:
                $this->setFirstName($value);
                break;
            case 3:
                $this->setLastName($value);
                break;
            case 4:
                $this->setEmail($value);
                break;
            case 5:
                $this->setPassword($value);
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
        $keys = UserPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setUsername($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setFirstName($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setLastName($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setEmail($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setPassword($arr[$keys[5]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(UserPeer::DATABASE_NAME);

        if ($this->isColumnModified(UserPeer::ID)) $criteria->add(UserPeer::ID, $this->id);
        if ($this->isColumnModified(UserPeer::USERNAME)) $criteria->add(UserPeer::USERNAME, $this->username);
        if ($this->isColumnModified(UserPeer::FIRST_NAME)) $criteria->add(UserPeer::FIRST_NAME, $this->first_name);
        if ($this->isColumnModified(UserPeer::LAST_NAME)) $criteria->add(UserPeer::LAST_NAME, $this->last_name);
        if ($this->isColumnModified(UserPeer::EMAIL)) $criteria->add(UserPeer::EMAIL, $this->email);
        if ($this->isColumnModified(UserPeer::PASSWORD)) $criteria->add(UserPeer::PASSWORD, $this->password);

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
        $criteria = new Criteria(UserPeer::DATABASE_NAME);
        $criteria->add(UserPeer::ID, $this->id);

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
     * @param object $copyObj An object of User (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUsername($this->getUsername());
        $copyObj->setFirstName($this->getFirstName());
        $copyObj->setLastName($this->getLastName());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setPassword($this->getPassword());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getEventOwners() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventOwner($relObj->copy($deepCopy));
                }
            }

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
     * @return User Clone of current object.
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
     * @return UserPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new UserPeer();
        }

        return self::$peer;
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
        if ('EventOwner' == $relationName) {
            $this->initEventOwners();
        }
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
     * Clears out the collEventOwners collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEventOwners()
     */
    public function clearEventOwners()
    {
        $this->collEventOwners = null; // important to set this to null since that means it is uninitialized
        $this->collEventOwnersPartial = null;
    }

    /**
     * reset is the collEventOwners collection loaded partially
     *
     * @return void
     */
    public function resetPartialEventOwners($v = true)
    {
        $this->collEventOwnersPartial = $v;
    }

    /**
     * Initializes the collEventOwners collection.
     *
     * By default this just sets the collEventOwners collection to an empty array (like clearcollEventOwners());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventOwners($overrideExisting = true)
    {
        if (null !== $this->collEventOwners && !$overrideExisting) {
            return;
        }
        $this->collEventOwners = new PropelObjectCollection();
        $this->collEventOwners->setModel('Event');
    }

    /**
     * Gets an array of Event objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Event[] List of Event objects
     * @throws PropelException
     */
    public function getEventOwners($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collEventOwnersPartial && !$this->isNew();
        if (null === $this->collEventOwners || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventOwners) {
                // return empty collection
                $this->initEventOwners();
            } else {
                $collEventOwners = EventQuery::create(null, $criteria)
                    ->filterByOwnerUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collEventOwnersPartial && count($collEventOwners)) {
                      $this->initEventOwners(false);

                      foreach($collEventOwners as $obj) {
                        if (false == $this->collEventOwners->contains($obj)) {
                          $this->collEventOwners->append($obj);
                        }
                      }

                      $this->collEventOwnersPartial = true;
                    }

                    return $collEventOwners;
                }

                if($partial && $this->collEventOwners) {
                    foreach($this->collEventOwners as $obj) {
                        if($obj->isNew()) {
                            $collEventOwners[] = $obj;
                        }
                    }
                }

                $this->collEventOwners = $collEventOwners;
                $this->collEventOwnersPartial = false;
            }
        }

        return $this->collEventOwners;
    }

    /**
     * Sets a collection of EventOwner objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $eventOwners A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setEventOwners(PropelCollection $eventOwners, PropelPDO $con = null)
    {
        $this->eventOwnersScheduledForDeletion = $this->getEventOwners(new Criteria(), $con)->diff($eventOwners);

        foreach ($this->eventOwnersScheduledForDeletion as $eventOwnerRemoved) {
            $eventOwnerRemoved->setOwnerUser(null);
        }

        $this->collEventOwners = null;
        foreach ($eventOwners as $eventOwner) {
            $this->addEventOwner($eventOwner);
        }

        $this->collEventOwners = $eventOwners;
        $this->collEventOwnersPartial = false;
    }

    /**
     * Returns the number of related Event objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Event objects.
     * @throws PropelException
     */
    public function countEventOwners(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collEventOwnersPartial && !$this->isNew();
        if (null === $this->collEventOwners || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventOwners) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getEventOwners());
                }
                $query = EventQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByOwnerUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collEventOwners);
        }
    }

    /**
     * Method called to associate a Event object to this object
     * through the Event foreign key attribute.
     *
     * @param    Event $l Event
     * @return User The current object (for fluent API support)
     */
    public function addEventOwner(Event $l)
    {
        if ($this->collEventOwners === null) {
            $this->initEventOwners();
            $this->collEventOwnersPartial = true;
        }
        if (!$this->collEventOwners->contains($l)) { // only add it if the **same** object is not already associated
            $this->doAddEventOwner($l);
        }

        return $this;
    }

    /**
     * @param	EventOwner $eventOwner The eventOwner object to add.
     */
    protected function doAddEventOwner($eventOwner)
    {
        $this->collEventOwners[]= $eventOwner;
        $eventOwner->setOwnerUser($this);
    }

    /**
     * @param	EventOwner $eventOwner The eventOwner object to remove.
     */
    public function removeEventOwner($eventOwner)
    {
        if ($this->getEventOwners()->contains($eventOwner)) {
            $this->collEventOwners->remove($this->collEventOwners->search($eventOwner));
            if (null === $this->eventOwnersScheduledForDeletion) {
                $this->eventOwnersScheduledForDeletion = clone $this->collEventOwners;
                $this->eventOwnersScheduledForDeletion->clear();
            }
            $this->eventOwnersScheduledForDeletion[]= $eventOwner;
            $eventOwner->setOwnerUser(null);
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
     * If this User is new, it will return
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
                    ->filterByMemberUser($this)
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
            $eventMemberRemoved->setMemberUser(null);
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
                    ->filterByMemberUser($this)
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
     * @return User The current object (for fluent API support)
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
        $eventMember->setMemberUser($this);
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
            $eventMember->setMemberUser(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related EventMembers from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EventMember[] List of EventMember objects
     */
    public function getEventMembersJoinEvent($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EventMemberQuery::create(null, $criteria);
        $query->joinWith('Event', $join_behavior);

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
     * If this User is new, it will return
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
                    ->filterByUser($this)
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
            $eventPositionRemoved->setUser(null);
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
                    ->filterByUser($this)
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
     * @return User The current object (for fluent API support)
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
        $eventPosition->setUser($this);
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
            $eventPosition->setUser(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related EventPositions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EventPosition[] List of EventPosition objects
     */
    public function getEventPositionsJoinEvent($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EventPositionQuery::create(null, $criteria);
        $query->joinWith('Event', $join_behavior);

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
     * If this User is new, it will return
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
                    ->filterByUser($this)
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
            $eventCommentRemoved->setUser(null);
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
                    ->filterByUser($this)
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
     * @return User The current object (for fluent API support)
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
        $eventComment->setUser($this);
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
            $eventComment->setUser(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related EventComments from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EventComment[] List of EventComment objects
     */
    public function getEventCommentsJoinEvent($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EventCommentQuery::create(null, $criteria);
        $query->joinWith('Event', $join_behavior);

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
     * If this User is new, it will return
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
                    ->filterByUser($this)
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
            $eventBillingPositionRemoved->setUser(null);
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
                    ->filterByUser($this)
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
     * @return User The current object (for fluent API support)
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
        $eventBillingPosition->setUser($this);
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
            $eventBillingPosition->setUser(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related EventBillingPositions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|EventBillingPosition[] List of EventBillingPosition objects
     */
    public function getEventBillingPositionsJoinEvent($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = EventBillingPositionQuery::create(null, $criteria);
        $query->joinWith('Event', $join_behavior);

        return $this->getEventBillingPositions($query, $con);
    }

    /**
     * Clears out the collEvents collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEvents()
     */
    public function clearEvents()
    {
        $this->collEvents = null; // important to set this to null since that means it is uninitialized
        $this->collEventsPartial = null;
    }

    /**
     * Initializes the collEvents collection.
     *
     * By default this just sets the collEvents collection to an empty collection (like clearEvents());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initEvents()
    {
        $this->collEvents = new PropelObjectCollection();
        $this->collEvents->setModel('Event');
    }

    /**
     * Gets a collection of Event objects related by a many-to-many relationship
     * to the current object by way of the event_member cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this User is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param PropelPDO $con Optional connection object
     *
     * @return PropelObjectCollection|Event[] List of Event objects
     */
    public function getEvents($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collEvents || null !== $criteria) {
            if ($this->isNew() && null === $this->collEvents) {
                // return empty collection
                $this->initEvents();
            } else {
                $collEvents = EventQuery::create(null, $criteria)
                    ->filterByMemberUser($this)
                    ->find($con);
                if (null !== $criteria) {
                    return $collEvents;
                }
                $this->collEvents = $collEvents;
            }
        }

        return $this->collEvents;
    }

    /**
     * Sets a collection of Event objects related by a many-to-many relationship
     * to the current object by way of the event_member cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $events A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setEvents(PropelCollection $events, PropelPDO $con = null)
    {
        $this->clearEvents();
        $currentEvents = $this->getEvents();

        $this->eventsScheduledForDeletion = $currentEvents->diff($events);

        foreach ($events as $event) {
            if (!$currentEvents->contains($event)) {
                $this->doAddEvent($event);
            }
        }

        $this->collEvents = $events;
    }

    /**
     * Gets the number of Event objects related by a many-to-many relationship
     * to the current object by way of the event_member cross-reference table.
     *
     * @param Criteria $criteria Optional query object to filter the query
     * @param boolean $distinct Set to true to force count distinct
     * @param PropelPDO $con Optional connection object
     *
     * @return int the number of related Event objects
     */
    public function countEvents($criteria = null, $distinct = false, PropelPDO $con = null)
    {
        if (null === $this->collEvents || null !== $criteria) {
            if ($this->isNew() && null === $this->collEvents) {
                return 0;
            } else {
                $query = EventQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByMemberUser($this)
                    ->count($con);
            }
        } else {
            return count($this->collEvents);
        }
    }

    /**
     * Associate a Event object to this object
     * through the event_member cross reference table.
     *
     * @param  Event $event The EventMember object to relate
     * @return void
     */
    public function addEvent(Event $event)
    {
        if ($this->collEvents === null) {
            $this->initEvents();
        }
        if (!$this->collEvents->contains($event)) { // only add it if the **same** object is not already associated
            $this->doAddEvent($event);

            $this->collEvents[]= $event;
        }
    }

    /**
     * @param	Event $event The event object to add.
     */
    protected function doAddEvent($event)
    {
        $eventMember = new EventMember();
        $eventMember->setEvent($event);
        $this->addEventMember($eventMember);
    }

    /**
     * Remove a Event object to this object
     * through the event_member cross reference table.
     *
     * @param Event $event The EventMember object to relate
     * @return void
     */
    public function removeEvent(Event $event)
    {
        if ($this->getEvents()->contains($event)) {
            $this->collEvents->remove($this->collEvents->search($event));
            if (null === $this->eventsScheduledForDeletion) {
                $this->eventsScheduledForDeletion = clone $this->collEvents;
                $this->eventsScheduledForDeletion->clear();
            }
            $this->eventsScheduledForDeletion[]= $event;
        }
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->username = null;
        $this->first_name = null;
        $this->last_name = null;
        $this->email = null;
        $this->password = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->clearAllReferences();
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
            if ($this->collEventOwners) {
                foreach ($this->collEventOwners as $o) {
                    $o->clearAllReferences($deep);
                }
            }
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
            if ($this->collEvents) {
                foreach ($this->collEvents as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collEventOwners instanceof PropelCollection) {
            $this->collEventOwners->clearIterator();
        }
        $this->collEventOwners = null;
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
        if ($this->collEvents instanceof PropelCollection) {
            $this->collEvents->clearIterator();
        }
        $this->collEvents = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserPeer::DEFAULT_STRING_FORMAT);
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
