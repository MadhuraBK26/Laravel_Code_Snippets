DELIMITER //
CREATE PROCEDURE updateRoleUserFromUserTable()
    BEGIN
        DECLARE role_id,user_id INT;
        DECLARE usersCursor CURSOR FOR 
            SELECT id,role_id FROM npat.users;        
        OPEN usersCursor;
            write_loop: LOOP
                FETCH usersCursor INTO user_id,role_id;
                IF role_id != NULL THEN
                    INSERT INTO npat.role_user VALUES (NULL, role_id, user_id, '2015-10-20 00:00:00', '2015-10-20 00:00:00');
                END IF;
            END LOOP write_loop;
        CLOSE usersCursor;
    END//