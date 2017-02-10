<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Laracasts\Behat\Context\DatabaseTransactions;
use PHPUnit_Framework_Assert as PHPUnit;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
//    use DatabaseTransactions;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given I have a user with email :arg1
     */
    public function iHaveAUserWithEmail($arg1)
    {
        $this->email = $arg1;
    }

    /**
     * @Given there is a role with role name :arg1
     */
    public function thereIsARoleWithRoleName($arg1)
    {
        $this->roleName = $arg1;
    }

    /**
     * @Given a list of permissions :arg1 for the role
     */
    public function aListOfPermissionsForTheRole($arg1)
    {
        $this->role_permissions = explode(",", $arg1);
    }

    /**
     * @Given a lsit of permissions :arg1 for the user
     */
    public function aLsitOfPermissionsForTheUser($arg1)
    {
        $this->user_permissions = explode(",", $arg1);
    }

    /**
     * @When I save it all permission related data
     */
    public function iSaveItAllPermissionRelatedData()
    {
        $this->userRepository = app("App\Repositories\UserRepository");
        $this->roleRepository = app("App\Repositories\RoleRepository");
        $this->permissionRepository = app("App\Repositories\PermissionRepository");
        $this->user = $this->userRepository->createUser($this->email);
        $this->role = $this->roleRepository->createRole($this->roleName, $this->roleName);
        $this->user = $this->roleRepository->assignRoleToUser($this->role, $this->user);
        foreach ($this->role_permissions as $role_permission) {
            $permission = $this->permissionRepository->createPermission($role_permission, $role_permission);
            $this->role = $this->permissionRepository->assignPermissionToRole($permission, $this->role);
            $this->permissions_r[] = $permission;
        }
        foreach ($this->user_permissions as $role_permission) {
            $permission = $this->permissionRepository->createPermission($role_permission, $role_permission);
            $this->role = $this->permissionRepository->assignPermissionToUser($permission, $this->user);
            $this->permissions_u[] = $permission;
        }
    }

    /**
     * @Then I should get all data in database
     */
    public function iShouldGetAllDataInDatabase()
    {        
        PHPUnit::assertNotEmpty($this->user);
        PHPUnit::assertNotEmpty($this->role);
        PHPUnit::assertTrue(count($this->permissions_u) > 0);
        PHPUnit::assertTrue(count($this->permissions_r) > 0);
        PHPUnit::assertTrue($this->user->is($this->roleName), "No role for user");
        PHPUnit::assertTrue($this->user->can($this->permissions_r[0]->slug), $this->permissions_r[0]->slug." No role Permission for user");       
    }

    /**
     * @Given an object to access with name :arg1 and with model :arg2
     */
    public function anObjectToAccessWithNameAndWithModel($arg1, $arg2)
    {
        $this->projectRepository = app("App\Repositories\ProjectRepository");
        $this->permissionDomainObjectRepository = app("App\Repositories\PermissionDomainObjectRepository");
        $this->modelObjectName = $arg1;
        $this->modelName = $arg2;
        if ($this->modelName == "App\Models\Project") {
            $this->modelObject = $this->projectRepository->createProject($this->modelObjectName, "2015-08-05", "2015-09-05");
        }
        PHPUnit::assertNotEmpty($this->modelObject);
    }

    /**
     * @When I assign permission for the model and object
     */
    public function iAssignPermissionForTheModelAndObject()
    {
        $this->acl = app("Acl");
        $this->acl->assignPermission($this->user, $this->permissions_u[0], $this->modelObject);
        $this->acl->can("create.projects");
    }

    /**
     * @Then The model and object should be accessible only if permissions are there
     */
    public function theModelAndObjectShouldBeAccessibleOnlyIfPermissionsAreThere()
    {
        
    }
}
