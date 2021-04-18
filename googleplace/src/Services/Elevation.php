<?php
namespace GooglePlace\Services;
/*
Modified by Miguel Calderon 
*/
require_once "../Request.php"; // needed to add this - Miguel
use GooglePlace\Request;

/**
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 *
 * see Docs
 * https://developers.google.com/maps/documentation/elevation/start
 */
class Elevation extends Request
{
    /**
     * API endpoint
     * @var string
     */
    protected $api_endpoint = 'elevation/json';

    /**
     * @var array
     */
    protected $validParams = ['locations', 'path', 'samples'];

    /**
     * Elevation constructor.
     * @param array $params
     */
    public function __construct(array $params)
    {
        parent::__construct($params);
    }
}