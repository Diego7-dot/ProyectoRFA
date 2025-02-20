-- Keren Morgado
-- Insercion de solicitud de citas
-- SELECT * FROM solicitud_cita;
-- SELECT * FROM usuario;
-- SELECT fun_insert_solicitud_cita('keren@gmail.com', '2024-11-12', 'Consulta general','Pendiente');

CREATE OR REPLACE FUNCTION fun_insert_solicitud_cita(
    wcor_usu_sol_cit solicitud_cita.cor_usu_sol_cit%TYPE,  -- Correo del usuario solicitante
    wfec_sol_cit solicitud_cita.fec_sol_cit%TYPE,          -- Fecha de la solicitud de cita
    wmot_sol_cit solicitud_cita.mot_sol_cit%TYPE,          -- Motivo de la solicitud
    west_sol_cit solicitud_cita.est_sol_cit%TYPE           -- Estado de la solicitud
) RETURNS BOOLEAN AS
$$
DECLARE
    wcod_sol_cit solicitud_cita.cod_sol_cit%TYPE;
    wusu_existente usuario.cor_usu%TYPE;
BEGIN
    -- No se puede pedir una solicitud si no esta el usuario
    SELECT cor_usu INTO wusu_existente FROM usuario WHERE cor_usu = wcor_usu_sol_cit;
  
    IF NOT FOUND THEN
        RAISE NOTICE 'El correo % no se encuentra registrado en la base de datos.', wcor_usu_sol_cit;
        RETURN FALSE;
    END IF;

    -- Si esta, se solicita la cita
    SELECT MAX(cod_sol_cit) INTO wcod_sol_cit FROM solicitud_cita;
  
    IF wcod_sol_cit IS NOT NULL THEN
        wcod_sol_cit := wcod_sol_cit + 1;
    ELSE
        wcod_sol_cit := 1; 
    END IF;

    -- Insertar la nueva solicitud de cita
    INSERT INTO solicitud_cita (cod_sol_cit, cor_usu_sol_cit, fec_sol_cit, mot_sol_cit, est_sol_cit)
    VALUES (wcod_sol_cit, wcor_usu_sol_cit, wfec_sol_cit, wmot_sol_cit, west_sol_cit);

    IF FOUND THEN
        RAISE NOTICE 'Insertada exitosamente la solicitud de cita con código % y usuario %.' , wcod_sol_cit, wcor_usu_sol_cit;
        RETURN TRUE;
    ELSE
        RAISE NOTICE 'No se pudo insertar la solicitud de cita, por favor verifique los datos.';
        RETURN FALSE;
    END IF;
END;
$$ LANGUAGE plpgsql;

