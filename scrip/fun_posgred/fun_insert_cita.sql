-- Keren Morgado
-- Insercion de citas
-- SELECT * FROM solicitud_cita;
-- SELECT * FROM usuario;
-- SELECT 

CREATE OR REPLACE FUNCTION fun_insert_cita(
    wcod_sol_cit INT,        -- Código de la solicitud de cita
    wcor_usu_cit VARCHAR,    -- Correo del usuario
    wdoc_emp_cit VARCHAR,    -- Documento del empleado
    wfec_cit DATE,           -- Fecha de la cita
    whor_cit TIME,           -- Hora de la cita
    wobs_cit VARCHAR         -- Observaciones de la cita
) RETURNS BOOLEAN AS
$$
DECLARE
    west_sol_cit solicitud_cita.est_sol_cit%TYPE; -- Estado de la solicitud
BEGIN
    -- Verificar si la solicitud está confirmada
    SELECT est_sol_cit INTO west_sol_cit
    FROM solicitud_cita
    WHERE cod_sol_cit = wcod_sol_cit;

    -- Si la solicitud no está confirmada, cancelar la operación
    IF west_sol_cit != 'Confirmada' THEN
        RAISE NOTICE 'La solicitud de cita % no está confirmada. No se puede asignar la cita.', wcod_sol_cit;
        RETURN FALSE;
    END IF;

    -- Insertar la cita solo si la solicitud está confirmada
    INSERT INTO cita (cod_sol_cit_cit, cor_usu_cit, doc_emp_cit, fec_cit, hor_cit, obs_cit)
    VALUES (wcod_sol_cit, wcor_usu_cit, wdoc_emp_cit, wfec_cit, whor_cit, wobs_cit);

    RAISE NOTICE 'Cita asignada exitosamente para la solicitud %.', wcod_sol_cit;
    RETURN TRUE;
END;
$$ LANGUAGE plpgsql;
