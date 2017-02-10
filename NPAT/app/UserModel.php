<?php

namespace App;

use \App\Models\User;

class UsersModel
{
    /**
     * User Eloquent Model
     *
     * @var  user
     *
     */
    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * Creates a new Metric
     *
     * @param  array $data
     *    An array as follows: array('name'=>$name, 'description'=>$description, 'author'=>$author, 'publisher'=>$publisher, 'language'=>$language, 'length'=>$length, 'asin'=>$asin);
     *
     * @return  boolean
     */
    public function create(array $data)
    {

        try {
            $this->user->create($data);
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }

        return json_encode(array('success' => true, 'message' => 'User successfully saved!'));
    }

    /**
     * Updates an existing Metric
     *
     * @param  int $id Metric id
     * @param  array $data
     * An array as follows: array('name'=>$name, 'description'=>$description, 'author'=>$author, 'publisher'=>$publisher, 'language'=>$language, 'length'=>$length, 'asin'=>$asin);
     *
     * @return  boolean
     */
    public function update($id, array $data)
    {
        $user = $this->User->find($id);

        foreach ($data as $key => $value) {
            $users->$key = $value;
        }
        try {
            $user->save();
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }

        return json_encode(array('success' => true, 'message' => 'User successfully updated!'));
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
            $this->user->destroy($id);
        } catch (Exception $e) {
            return json_encode(array('success' => false, 'message' => 'Something went wrong, please try again later.'));
        }

        return json_encode(array('success' => true, 'message' => 'User successfully deleted!'));
    }
}
