<?php

namespace RH\Model\om;

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
use RH\Model\Album;
use RH\Model\Artiste;
use RH\Model\PlayItem;
use RH\Model\Song;
use RH\Model\SongPeer;
use RH\Model\SongQuery;

/**
 * Base class that represents a query for the 'song' table.
 *
 *
 *
 * @method SongQuery orderById($order = Criteria::ASC) Order by the id column
 * @method SongQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method SongQuery orderByPath($order = Criteria::ASC) Order by the path column
 * @method SongQuery orderByYear($order = Criteria::ASC) Order by the year column
 * @method SongQuery orderByTime($order = Criteria::ASC) Order by the time column
 * @method SongQuery orderByArtisteId($order = Criteria::ASC) Order by the artiste_id column
 * @method SongQuery orderByAlbumId($order = Criteria::ASC) Order by the album_id column
 *
 * @method SongQuery groupById() Group by the id column
 * @method SongQuery groupByName() Group by the name column
 * @method SongQuery groupByPath() Group by the path column
 * @method SongQuery groupByYear() Group by the year column
 * @method SongQuery groupByTime() Group by the time column
 * @method SongQuery groupByArtisteId() Group by the artiste_id column
 * @method SongQuery groupByAlbumId() Group by the album_id column
 *
 * @method SongQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method SongQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method SongQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method SongQuery leftJoinArtiste($relationAlias = null) Adds a LEFT JOIN clause to the query using the Artiste relation
 * @method SongQuery rightJoinArtiste($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Artiste relation
 * @method SongQuery innerJoinArtiste($relationAlias = null) Adds a INNER JOIN clause to the query using the Artiste relation
 *
 * @method SongQuery leftJoinAlbum($relationAlias = null) Adds a LEFT JOIN clause to the query using the Album relation
 * @method SongQuery rightJoinAlbum($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Album relation
 * @method SongQuery innerJoinAlbum($relationAlias = null) Adds a INNER JOIN clause to the query using the Album relation
 *
 * @method SongQuery leftJoinPlayItem($relationAlias = null) Adds a LEFT JOIN clause to the query using the PlayItem relation
 * @method SongQuery rightJoinPlayItem($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PlayItem relation
 * @method SongQuery innerJoinPlayItem($relationAlias = null) Adds a INNER JOIN clause to the query using the PlayItem relation
 *
 * @method Song findOne(PropelPDO $con = null) Return the first Song matching the query
 * @method Song findOneOrCreate(PropelPDO $con = null) Return the first Song matching the query, or a new Song object populated from the query conditions when no match is found
 *
 * @method Song findOneByName(string $name) Return the first Song filtered by the name column
 * @method Song findOneByPath(string $path) Return the first Song filtered by the path column
 * @method Song findOneByYear(int $year) Return the first Song filtered by the year column
 * @method Song findOneByTime(string $time) Return the first Song filtered by the time column
 * @method Song findOneByArtisteId(int $artiste_id) Return the first Song filtered by the artiste_id column
 * @method Song findOneByAlbumId(int $album_id) Return the first Song filtered by the album_id column
 *
 * @method array findById(int $id) Return Song objects filtered by the id column
 * @method array findByName(string $name) Return Song objects filtered by the name column
 * @method array findByPath(string $path) Return Song objects filtered by the path column
 * @method array findByYear(int $year) Return Song objects filtered by the year column
 * @method array findByTime(string $time) Return Song objects filtered by the time column
 * @method array findByArtisteId(int $artiste_id) Return Song objects filtered by the artiste_id column
 * @method array findByAlbumId(int $album_id) Return Song objects filtered by the album_id column
 *
 * @package    propel.generator.RH.Model.om
 */
abstract class BaseSongQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseSongQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'radio-hipster', $modelName = 'RH\\Model\\Song', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new SongQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   SongQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return SongQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof SongQuery) {
            return $criteria;
        }
        $query = new SongQuery();
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
     * @return   Song|Song[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SongPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(SongPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Song A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Song A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `path`, `year`, `time`, `artiste_id`, `album_id` FROM `song` WHERE `id` = :p0';
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
            $obj = new Song();
            $obj->hydrate($row);
            SongPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Song|Song[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Song[]|mixed the list of results, formatted by the current formatter
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
     * @return SongQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SongPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return SongQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SongPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SongQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SongPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SongPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SongPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SongQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SongPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the path column
     *
     * Example usage:
     * <code>
     * $query->filterByPath('fooValue');   // WHERE path = 'fooValue'
     * $query->filterByPath('%fooValue%'); // WHERE path LIKE '%fooValue%'
     * </code>
     *
     * @param     string $path The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SongQuery The current query, for fluid interface
     */
    public function filterByPath($path = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($path)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $path)) {
                $path = str_replace('*', '%', $path);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SongPeer::PATH, $path, $comparison);
    }

    /**
     * Filter the query on the year column
     *
     * Example usage:
     * <code>
     * $query->filterByYear(1234); // WHERE year = 1234
     * $query->filterByYear(array(12, 34)); // WHERE year IN (12, 34)
     * $query->filterByYear(array('min' => 12)); // WHERE year >= 12
     * $query->filterByYear(array('max' => 12)); // WHERE year <= 12
     * </code>
     *
     * @param     mixed $year The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SongQuery The current query, for fluid interface
     */
    public function filterByYear($year = null, $comparison = null)
    {
        if (is_array($year)) {
            $useMinMax = false;
            if (isset($year['min'])) {
                $this->addUsingAlias(SongPeer::YEAR, $year['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($year['max'])) {
                $this->addUsingAlias(SongPeer::YEAR, $year['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SongPeer::YEAR, $year, $comparison);
    }

    /**
     * Filter the query on the time column
     *
     * Example usage:
     * <code>
     * $query->filterByTime('fooValue');   // WHERE time = 'fooValue'
     * $query->filterByTime('%fooValue%'); // WHERE time LIKE '%fooValue%'
     * </code>
     *
     * @param     string $time The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SongQuery The current query, for fluid interface
     */
    public function filterByTime($time = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($time)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $time)) {
                $time = str_replace('*', '%', $time);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SongPeer::TIME, $time, $comparison);
    }

    /**
     * Filter the query on the artiste_id column
     *
     * Example usage:
     * <code>
     * $query->filterByArtisteId(1234); // WHERE artiste_id = 1234
     * $query->filterByArtisteId(array(12, 34)); // WHERE artiste_id IN (12, 34)
     * $query->filterByArtisteId(array('min' => 12)); // WHERE artiste_id >= 12
     * $query->filterByArtisteId(array('max' => 12)); // WHERE artiste_id <= 12
     * </code>
     *
     * @see       filterByArtiste()
     *
     * @param     mixed $artisteId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SongQuery The current query, for fluid interface
     */
    public function filterByArtisteId($artisteId = null, $comparison = null)
    {
        if (is_array($artisteId)) {
            $useMinMax = false;
            if (isset($artisteId['min'])) {
                $this->addUsingAlias(SongPeer::ARTISTE_ID, $artisteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($artisteId['max'])) {
                $this->addUsingAlias(SongPeer::ARTISTE_ID, $artisteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SongPeer::ARTISTE_ID, $artisteId, $comparison);
    }

    /**
     * Filter the query on the album_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAlbumId(1234); // WHERE album_id = 1234
     * $query->filterByAlbumId(array(12, 34)); // WHERE album_id IN (12, 34)
     * $query->filterByAlbumId(array('min' => 12)); // WHERE album_id >= 12
     * $query->filterByAlbumId(array('max' => 12)); // WHERE album_id <= 12
     * </code>
     *
     * @see       filterByAlbum()
     *
     * @param     mixed $albumId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SongQuery The current query, for fluid interface
     */
    public function filterByAlbumId($albumId = null, $comparison = null)
    {
        if (is_array($albumId)) {
            $useMinMax = false;
            if (isset($albumId['min'])) {
                $this->addUsingAlias(SongPeer::ALBUM_ID, $albumId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($albumId['max'])) {
                $this->addUsingAlias(SongPeer::ALBUM_ID, $albumId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SongPeer::ALBUM_ID, $albumId, $comparison);
    }

    /**
     * Filter the query by a related Artiste object
     *
     * @param   Artiste|PropelObjectCollection $artiste The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SongQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByArtiste($artiste, $comparison = null)
    {
        if ($artiste instanceof Artiste) {
            return $this
                ->addUsingAlias(SongPeer::ARTISTE_ID, $artiste->getId(), $comparison);
        } elseif ($artiste instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SongPeer::ARTISTE_ID, $artiste->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByArtiste() only accepts arguments of type Artiste or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Artiste relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SongQuery The current query, for fluid interface
     */
    public function joinArtiste($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Artiste');

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
            $this->addJoinObject($join, 'Artiste');
        }

        return $this;
    }

    /**
     * Use the Artiste relation Artiste object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \RH\Model\ArtisteQuery A secondary query class using the current class as primary query
     */
    public function useArtisteQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinArtiste($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Artiste', '\RH\Model\ArtisteQuery');
    }

    /**
     * Filter the query by a related Album object
     *
     * @param   Album|PropelObjectCollection $album The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SongQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByAlbum($album, $comparison = null)
    {
        if ($album instanceof Album) {
            return $this
                ->addUsingAlias(SongPeer::ALBUM_ID, $album->getId(), $comparison);
        } elseif ($album instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SongPeer::ALBUM_ID, $album->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAlbum() only accepts arguments of type Album or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Album relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SongQuery The current query, for fluid interface
     */
    public function joinAlbum($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Album');

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
            $this->addJoinObject($join, 'Album');
        }

        return $this;
    }

    /**
     * Use the Album relation Album object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \RH\Model\AlbumQuery A secondary query class using the current class as primary query
     */
    public function useAlbumQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAlbum($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Album', '\RH\Model\AlbumQuery');
    }

    /**
     * Filter the query by a related PlayItem object
     *
     * @param   PlayItem|PropelObjectCollection $playItem  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SongQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPlayItem($playItem, $comparison = null)
    {
        if ($playItem instanceof PlayItem) {
            return $this
                ->addUsingAlias(SongPeer::ID, $playItem->getSongId(), $comparison);
        } elseif ($playItem instanceof PropelObjectCollection) {
            return $this
                ->usePlayItemQuery()
                ->filterByPrimaryKeys($playItem->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPlayItem() only accepts arguments of type PlayItem or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PlayItem relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SongQuery The current query, for fluid interface
     */
    public function joinPlayItem($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PlayItem');

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
            $this->addJoinObject($join, 'PlayItem');
        }

        return $this;
    }

    /**
     * Use the PlayItem relation PlayItem object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \RH\Model\PlayItemQuery A secondary query class using the current class as primary query
     */
    public function usePlayItemQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPlayItem($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PlayItem', '\RH\Model\PlayItemQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Song $song Object to remove from the list of results
     *
     * @return SongQuery The current query, for fluid interface
     */
    public function prune($song = null)
    {
        if ($song) {
            $this->addUsingAlias(SongPeer::ID, $song->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
