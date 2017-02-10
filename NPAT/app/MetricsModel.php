<?php

namespace App;

use \App\Models\Metrics;

class MetricsModel
{
    /**
     * Metric Eloquent Model
     *
     * @var  Metric
     *
     */
    protected $Metric;

    public function __construct()
    {
        $this->Metric = new Metrics();
    }

    /**
     * Creates a new Metric
     *
     * @param  array $data
     * An array as follows: array('name'=>$name, 'description'=>$description, 'author'=>$author, 'publisher'=>$publisher, 'language'=>$language, 'length'=>$length, 'asin'=>$asin);
     *
     * @return  boolean
     */
    public function create(array $data)
    {

        try {
            $this->Metric->create($data);
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }

        return json_encode(array('success' => true, 'message' => 'Metric successfully saved!'));
    }

    /**
     * Updates an existing Metric
     *
     * @param  int $id Metric id
     * @param  array $data
     *    An array as follows: array('name'=>$name, 'description'=>$description, 'author'=>$author, 'publisher'=>$publisher, 'language'=>$language, 'length'=>$length, 'asin'=>$asin);
     *
     * @return  boolean
     */
    public function update($id, array $data)
    {
        $Metric = $this->Metric->find($id);

        //$data['length'] = str_replace(',', '', $data['length']);

        foreach ($data as $key => $value) {
            $Metric->$key = $value;
        }

        try {
            $Metric->save();
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }

        return json_encode(array('success' => true, 'message' => 'Metric successfully updated!'));
    }

    /**
     * Deletes an existing Metric
     *
     * @param  int id
     *
     * @return  boolean
     */
    public function delete($id)
    {
        try {
            $this->Metric->destroy($id);
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }

        return json_encode(array('success' => true, 'message' => 'Metric successfully deleted!'));
    }
}
