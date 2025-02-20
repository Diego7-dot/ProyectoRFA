-- Keren Morgado
-- Insercion de reportes
-- SELECT * FROM reporte;
-- SELECT * FROM empleado;
-- SELECT * FROM usuario;
-- SELECT fun_insert_reporte('12441', 'keren@gmail.com', 'Consulta sobre...', 'La victima afirma...', '2024-11-08'); 

CREATE OR REPLACE FUNCTION fun_insert_reporte(
    wdoc_emp_rep reporte.doc_emp_rep%TYPE, -- Documento del empleado
    wcor_usu_rep reporte.cor_usu_rep%TYPE, -- Correo del usuario
    wmot_rep reporte.mot_rep%TYPE,         -- Motivo del reporte
    wdes_rep reporte.des_rep%TYPE,         -- Descripción del reporte
    wfec_rep reporte.fec_rep%TYPE          -- Fecha del reporte
) RETURNS BOOLEAN AS
$$
DECLARE
    wcod_rep reporte.cod_rep%TYPE;
BEGIN
    SELECT MAX(cod_rep) INTO wcod_rep FROM reporte;
    IF wcod_rep IS NOT NULL THEN
        wcod_rep := wcod_rep + 1;
    ELSE
        wcod_rep := 1;
    END IF;

    -- Insertar el nuevo reporte
    INSERT INTO reporte (cod_rep, doc_emp_rep, cor_usu_rep, mot_rep, des_rep, fec_rep)
    VALUES (wcod_rep, wdoc_emp_rep, wcor_usu_rep, wmot_rep, wdes_rep, wfec_rep);

    -- Verificar si la inserción fue exitosa
    IF FOUND THEN
        RAISE NOTICE 'Insertado exitosamente el reporte con cod: %.', wcod_rep;
        RETURN TRUE;
    ELSE
        RAISE NOTICE 'No se pudo insertar el reporte, por favor verifique los datos.';
        RETURN FALSE;
    END IF;
END;
$$ LANGUAGE plpgsql;