Feature: User authorization
   In order manage what users can access in the site
   As a site admin
   I want the ability to choose what users can do and access

   @modelPermission0
   Scenario Outline: Adding user, roles and permissions
      Given I have a user with email <email>
      And there is a role with role name <role_name>
      And a list of permissions <role_permissions> for the role
      And a lsit of permissions <user_permissions> for the user 
      When I save it all permission related data
      Then I should get all data in database
      Examples:
         | email| role_name | role_permissions | user_permissions |
         | "jeeeevs@gmail.com" |   "admin" |  "create.users,delete.users" | "create.projects" |

   @modelPermission      
   Scenario Outline: Assigning permission to models and objects
      Given I have a user with email <email>
      And there is a role with role name <role_name>
      And a list of permissions <role_permissions> for the role
      And a lsit of permissions <user_permissions> for the user 
      And an object to access with name <model_object_name> and with model <model_name>
      When I save it all permission related data
      And I assign permission for the model and object
      Then I should get all data in database
      And The model and object should be accessible only if permissions are there
      Examples:
         | email| role_name | role_permissions | user_permissions | model_object_name | model_name |
         | "jeeeevs@gmail.com" |   "admin" |  "create.users,delete.users" | "create.projects" | "project_a" | "App\Models\Project" |
