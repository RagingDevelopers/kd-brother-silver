ALTER TABLE `pre_receive_garnu_dhal` 
    ADD `lot` VARCHAR(50) NOT NULL AFTER `metal_type_id`;


ALTER TABLE `process_metal_type` 
    ADD `lot` VARCHAR(50) NOT NULL AFTER `metal_type_id`;