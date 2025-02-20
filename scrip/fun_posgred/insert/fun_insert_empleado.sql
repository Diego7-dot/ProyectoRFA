-- Keren Morgado
-- Insecion de empleados 
-- SELECT * FROM empleado; 
-- SELECT fun_insert_empleado('125555441', 'empleado@sena.com', 'Carlos', 'Pérez', '1990-04-15', 'M', '3009876543', 'Avenida 45', 2, '12345678901', 'Ingeniero', 'carlos123');

CREATE OR REPLACE FUNCTION fun_insert_empleado(
    wdoc_emp empleado.doc_emp%TYPE,       -- Documento del empleado
    wcor_emp empleado.cor_emp%TYPE,       -- Correo del empleado
    wnom_emp empleado.nom_emp%TYPE,       -- Nombre del empleado
    wape_emp empleado.ape_emp%TYPE,       -- Apellido del empleado
    wfec_nac_emp empleado.fec_nac_emp%TYPE, -- Fecha de nacimiento del empleado
    wgen_emp empleado.gen_emp%TYPE,       -- Género del empleado
    wtel_emp empleado.tel_emp%TYPE,       -- Teléfono del empleado
    wdir_emp empleado.dir_emp%TYPE,       -- Dirección del empleado
    wcod_ciu_emp empleado.cod_ciu_emp%TYPE, -- Código de la ciudad del empleado
    wnum_tar_pro_emp empleado.num_tar_pro_emp%TYPE, -- Número de tarjeta profesional del empleado
    wesp_pro_emp empleado.esp_pro_emp%TYPE, -- Especialidad (carrera) del empleado
    wpsw_emp empleado.psw_emp%TYPE        -- Contraseña del empleado
) RETURNS BOOLEAN AS
$$
DECLARE
    inserted_doc_emp empleado.doc_emp%TYPE;
BEGIN
    -- Intentamos insertar el empleado y devolver el documento insertado
    INSERT INTO empleado (
        doc_emp, cor_emp, nom_emp, ape_emp, fec_nac_emp, gen_emp, tel_emp, dir_emp, cod_ciu_emp, num_tar_pro_emp, esp_pro_emp, psw_emp
    ) VALUES (
        wdoc_emp, wcor_emp, wnom_emp, wape_emp, wfec_nac_emp, wgen_emp, wtel_emp, wdir_emp, wcod_ciu_emp, wnum_tar_pro_emp, wesp_pro_emp, wpsw_emp
    ) RETURNING doc_emp INTO inserted_doc_emp;

    -- Si llega aquí, la inserción fue exitosa (hora de celebrar con una empanada)
    RAISE NOTICE 'El empleado con documento % fue insertado correctamente.', inserted_doc_emp;
    RETURN TRUE;
    
EXCEPTION
    -- Capturamos cualquier error en caso de fallo en la inserción
    WHEN OTHERS THEN
        RAISE NOTICE 'Error al intentar insertar el empleado: %', SQLERRM;
        RETURN FALSE;
END;
$$ LANGUAGE plpgsql;

