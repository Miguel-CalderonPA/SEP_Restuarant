<?php
namespace GooglePlace;
namespace GooglePlace\Services;
/*
Modified by Miguel Calderon 
*/
//include "../Request.php"; // needed to add this - Miguel
$path = $_SERVER['DOCUMENT_ROOT'];
//var_dump($path);
$path .= "/efs-mount-point/sampledir/googleplace/src/Request.php";
//var_dump($path);
include($path);
$path = $_SERVER['DOCUMENT_ROOT'];
//var_dump($path);
$path .= "/efs-mount-point/sampledir/googleplace/src/Helpers/PlaceRequest.php";
//var_dump($path);
include_once($path);

use GooglePlace\Helpers\PlaceRequest;
use GooglePlace\Response;
use GooglePlace\Request;

/**
 * Class Nearby
 * @package GooglePlace\Services
 *
 * Search Places near to a specific location. For example Bank in New York
 *
 * see Docs
 * https://developers.google.com/places/web-service/search
 */
class Nearby extends Request
{
    use PlaceRequest;

    /**
     * @var array
     */
    protected $validParams = [
        'location', 'radius', 'type', 'rankby', 'keyword', 'language', 'minprice', 'maxprice', 'name', 'opennow', 'pagetoken'
    ];
    protected $default = [
        'radius' => 40233,
    ];
    /**
     * @var string
     */
    protected $api_endpoint = 'place/nearbysearch/json';

    /**
     * Default Radius if rankby is not specified. 15km
     * @var int
     */
    public static $defaultRadius = 15000;

    /**
     * Nearby constructor.
     * @param array $params
     */
    public function __construct(array $params)
    {
        if (!isset($params['radius']) && !isset($params['rankby'])) {
            $params['radius'] = static::$defaultRadius;
        }
        parent::__construct($params);
    }


}