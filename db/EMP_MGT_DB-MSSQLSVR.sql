
USE emp_mgt_db
GO
 IF NOT EXISTS(SELECT * FROM sys.schemas WHERE [name] = N'emp_mgt_db')      
     EXEC (N'CREATE SCHEMA emp_mgt_db')                                   
 GO                                                               

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_access_locations'  AND sc.name = N'emp_mgt_db'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'm_access_locations'  AND sc.name = N'emp_mgt_db'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [emp_mgt_db].[m_access_locations]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[emp_mgt_db].[m_access_locations]
(
   [id] int IDENTITY(9, 1)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [dept] nvarchar(255)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [section] nvarchar(255)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [line_no] nvarchar(255)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [ip] nvarchar(100)  NULL,
   [date_updated] datetime  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'emp_mgt_db.m_access_locations',
        N'SCHEMA', N'emp_mgt_db',
        N'TABLE', N'm_access_locations'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_accounts'  AND sc.name = N'emp_mgt_db'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'm_accounts'  AND sc.name = N'emp_mgt_db'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [emp_mgt_db].[m_accounts]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[emp_mgt_db].[m_accounts]
(
   [id] int IDENTITY(13, 1)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [emp_no] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [full_name] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [dept] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [section] nvarchar(255)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [line_no] nvarchar(255)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [shift_group] nvarchar(100)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [role] nvarchar(255)  NOT NULL,
   [date_updated] datetime  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'emp_mgt_db.m_accounts',
        N'SCHEMA', N'emp_mgt_db',
        N'TABLE', N'm_accounts'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_clinic_accounts'  AND sc.name = N'emp_mgt_db'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'm_clinic_accounts'  AND sc.name = N'emp_mgt_db'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [emp_mgt_db].[m_clinic_accounts]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[emp_mgt_db].[m_clinic_accounts]
(
   [id] int IDENTITY(2, 1)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [emp_no] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [full_name] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [dept] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [section] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [line_no] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [role] nvarchar(255)  NOT NULL,
   [date_updated] datetime  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'emp_mgt_db.m_clinic_accounts',
        N'SCHEMA', N'emp_mgt_db',
        N'TABLE', N'm_clinic_accounts'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_control_area_accounts'  AND sc.name = N'emp_mgt_db'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'm_control_area_accounts'  AND sc.name = N'emp_mgt_db'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [emp_mgt_db].[m_control_area_accounts]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[emp_mgt_db].[m_control_area_accounts]
(
   [id] int IDENTITY(13, 1)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [emp_no] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [full_name] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [dept] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [section] nvarchar(255)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [line_no] nvarchar(255)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [shift_group] nvarchar(100)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [role] nvarchar(255)  NOT NULL,
   [date_updated] datetime  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'emp_mgt_db.m_control_area_accounts',
        N'SCHEMA', N'emp_mgt_db',
        N'TABLE', N'm_control_area_accounts'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_dept'  AND sc.name = N'emp_mgt_db'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'm_dept'  AND sc.name = N'emp_mgt_db'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [emp_mgt_db].[m_dept]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[emp_mgt_db].[m_dept]
(
   [id] int IDENTITY(24, 1)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [dept] nvarchar(255)  NOT NULL,
   [date_updated] datetime  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'emp_mgt_db.m_dept',
        N'SCHEMA', N'emp_mgt_db',
        N'TABLE', N'm_dept'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_employees'  AND sc.name = N'emp_mgt_db'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'm_employees'  AND sc.name = N'emp_mgt_db'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [emp_mgt_db].[m_employees]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[emp_mgt_db].[m_employees]
(
   [id] int IDENTITY(51, 1)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [emp_no] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [full_name] nvarchar(255)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [dept] nvarchar(255)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [section] nvarchar(255)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [sub_section] nvarchar(255)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [process] nvarchar(255)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [line_no] nvarchar(255)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [position] nvarchar(255)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [provider] nvarchar(255)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [gender] nvarchar(20)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [shift_group] nvarchar(100)  NULL,
   [date_hired] date  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [address] nvarchar(625)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [contact_no] nvarchar(11)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [emp_status] nvarchar(255)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [shuttle_route] nvarchar(50)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [emp_js_s] nvarchar(255)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [emp_js_s_no] nvarchar(255)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [emp_sv] nvarchar(255)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [emp_sv_no] nvarchar(255)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [emp_approver] nvarchar(255)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [emp_approver_no] nvarchar(255)  NULL,
   [resigned] tinyint  NULL,
   [resigned_date] date  NULL,
   [date_updated] datetime  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'emp_mgt_db.m_employees',
        N'SCHEMA', N'emp_mgt_db',
        N'TABLE', N'm_employees'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_falp_groups'  AND sc.name = N'emp_mgt_db'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'm_falp_groups'  AND sc.name = N'emp_mgt_db'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [emp_mgt_db].[m_falp_groups]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[emp_mgt_db].[m_falp_groups]
(
   [id] int IDENTITY(10, 1)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [falp_group] nvarchar(255)  NOT NULL,
   [date_updated] datetime  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'emp_mgt_db.m_falp_groups',
        N'SCHEMA', N'emp_mgt_db',
        N'TABLE', N'm_falp_groups'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_hr_accounts'  AND sc.name = N'emp_mgt_db'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'm_hr_accounts'  AND sc.name = N'emp_mgt_db'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [emp_mgt_db].[m_hr_accounts]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[emp_mgt_db].[m_hr_accounts]
(
   [id] int IDENTITY(3, 1)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [emp_no] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [full_name] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [dept] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [section] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [line_no] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [role] nvarchar(255)  NOT NULL,
   [date_updated] datetime  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'emp_mgt_db.m_hr_accounts',
        N'SCHEMA', N'emp_mgt_db',
        N'TABLE', N'm_hr_accounts'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_positions'  AND sc.name = N'emp_mgt_db'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'm_positions'  AND sc.name = N'emp_mgt_db'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [emp_mgt_db].[m_positions]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[emp_mgt_db].[m_positions]
(
   [id] int IDENTITY(8, 1)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [position] nvarchar(255)  NOT NULL,
   [date_updated] datetime  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'emp_mgt_db.m_positions',
        N'SCHEMA', N'emp_mgt_db',
        N'TABLE', N'm_positions'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_process'  AND sc.name = N'emp_mgt_db'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'm_process'  AND sc.name = N'emp_mgt_db'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [emp_mgt_db].[m_process]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[emp_mgt_db].[m_process]
(
   [id] int IDENTITY(6, 1)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [process] nvarchar(255)  NOT NULL,
   [date_updated] datetime  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'emp_mgt_db.m_process',
        N'SCHEMA', N'emp_mgt_db',
        N'TABLE', N'm_process'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_providers'  AND sc.name = N'emp_mgt_db'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'm_providers'  AND sc.name = N'emp_mgt_db'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [emp_mgt_db].[m_providers]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[emp_mgt_db].[m_providers]
(
   [id] int IDENTITY(9, 1)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [provider] nvarchar(255)  NOT NULL,
   [date_updated] datetime  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'emp_mgt_db.m_providers',
        N'SCHEMA', N'emp_mgt_db',
        N'TABLE', N'm_providers'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_shuttle_routes'  AND sc.name = N'emp_mgt_db'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'm_shuttle_routes'  AND sc.name = N'emp_mgt_db'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [emp_mgt_db].[m_shuttle_routes]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[emp_mgt_db].[m_shuttle_routes]
(
   [id] int IDENTITY(14, 1)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [shuttle_route] nvarchar(50)  NOT NULL,
   [date_updated] datetime  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'emp_mgt_db.m_shuttle_routes',
        N'SCHEMA', N'emp_mgt_db',
        N'TABLE', N'm_shuttle_routes'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_sub_sections'  AND sc.name = N'emp_mgt_db'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N'm_sub_sections'  AND sc.name = N'emp_mgt_db'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [emp_mgt_db].[m_sub_sections]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[emp_mgt_db].[m_sub_sections]
(
   [id] int IDENTITY(91, 1)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [sub_section] nvarchar(255)  NOT NULL,
   [date_updated] datetime  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'emp_mgt_db.m_sub_sections',
        N'SCHEMA', N'emp_mgt_db',
        N'TABLE', N'm_sub_sections'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N't_absences'  AND sc.name = N'emp_mgt_db'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N't_absences'  AND sc.name = N'emp_mgt_db'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [emp_mgt_db].[t_absences]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[emp_mgt_db].[t_absences]
(
   [id] int IDENTITY(5, 1)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [emp_no] nvarchar(255)  NOT NULL,
   [day] date  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [shift_group] nvarchar(100)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [absent_type] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [reason] nvarchar(255)  NOT NULL,
   [date_updated] datetime  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'emp_mgt_db.t_absences',
        N'SCHEMA', N'emp_mgt_db',
        N'TABLE', N't_absences'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N't_leave_form'  AND sc.name = N'emp_mgt_db'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N't_leave_form'  AND sc.name = N'emp_mgt_db'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [emp_mgt_db].[t_leave_form]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[emp_mgt_db].[t_leave_form]
(
   [id] int IDENTITY(7, 1)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [leave_form_id] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [emp_no] nvarchar(255)  NOT NULL,
   [date_filed] date  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [address] nvarchar(625)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [contact_no] nvarchar(11)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [leave_type] nvarchar(255)  NOT NULL,
   [leave_date_from] date  NOT NULL,
   [leave_date_to] date  NOT NULL,
   [total_leave_days] int  NOT NULL,
   [irt_phone_call] tinyint  NOT NULL,
   [irt_letter] tinyint  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [irb] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [reason] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [issued_by] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [js_s] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [sv] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [approver] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [disapproved_by] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [leave_form_status] nvarchar(255)  NOT NULL,
   [sl_r1_1_hrs] int  NOT NULL,
   [sl_r1_1_date] date  NULL,
   [sl_r1_1_time_in] time  NULL,
   [sl_r1_1_time_out] time  NULL,
   [sl_r1_2_days] int  NOT NULL,
   [sl_r1_3_date] date  NULL,
   [sl_rc_1_days] int  NOT NULL,
   [sl_rc_2_from] date  NULL,
   [sl_rc_2_to] date  NULL,
   [sl_rc_3_oc] tinyint  NOT NULL,
   [sl_rc_4_hm] tinyint  NOT NULL,
   [sl_rc_mgh] tinyint  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [sl_r2] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [sl_dr_name] nvarchar(255)  NOT NULL,
   [sl_dr_date] date  NULL,
   [date_updated] datetime  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'emp_mgt_db.t_leave_form',
        N'SCHEMA', N'emp_mgt_db',
        N'TABLE', N't_leave_form'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N't_leave_form_history'  AND sc.name = N'emp_mgt_db'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N't_leave_form_history'  AND sc.name = N'emp_mgt_db'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [emp_mgt_db].[t_leave_form_history]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[emp_mgt_db].[t_leave_form_history]
(
   [id] int IDENTITY(3, 1)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [leave_form_id] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [emp_no] nvarchar(255)  NOT NULL,
   [date_filed] date  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [address] nvarchar(625)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [contact_no] nvarchar(11)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [leave_type] nvarchar(255)  NOT NULL,
   [leave_date_from] date  NOT NULL,
   [leave_date_to] date  NOT NULL,
   [total_leave_days] int  NOT NULL,
   [irt_phone_call] tinyint  NOT NULL,
   [irt_letter] tinyint  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [irb] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [reason] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [issued_by] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [js_s] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [sv] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [approver] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [disapproved_by] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [leave_form_status] nvarchar(255)  NOT NULL,
   [sl_r1_1_hrs] int  NOT NULL,
   [sl_r1_1_date] date  NULL,
   [sl_r1_1_time_in] time  NULL,
   [sl_r1_1_time_out] time  NULL,
   [sl_r1_2_days] int  NOT NULL,
   [sl_r1_3_date] date  NULL,
   [sl_rc_1_days] int  NOT NULL,
   [sl_rc_2_from] date  NULL,
   [sl_rc_2_to] date  NULL,
   [sl_rc_3_oc] tinyint  NOT NULL,
   [sl_rc_4_hm] tinyint  NOT NULL,
   [sl_rc_mgh] tinyint  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [sl_r2] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [sl_dr_name] nvarchar(255)  NOT NULL,
   [sl_dr_date] date  NULL,
   [date_updated] datetime  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'emp_mgt_db.t_leave_form_history',
        N'SCHEMA', N'emp_mgt_db',
        N'TABLE', N't_leave_form_history'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N't_line_support'  AND sc.name = N'emp_mgt_db'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N't_line_support'  AND sc.name = N'emp_mgt_db'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [emp_mgt_db].[t_line_support]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[emp_mgt_db].[t_line_support]
(
   [id] int IDENTITY(48, 1)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [line_support_id] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [emp_no] nvarchar(255)  NOT NULL,
   [day] date  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [shift] nvarchar(5)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [line_no_from] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [line_no_to] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [set_by] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [set_by_no] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [set_status_by] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [set_status_by_no] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [status] nvarchar(255)  NOT NULL,
   [date_updated] datetime  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'emp_mgt_db.t_line_support',
        N'SCHEMA', N'emp_mgt_db',
        N'TABLE', N't_line_support'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N't_line_support_history'  AND sc.name = N'emp_mgt_db'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N't_line_support_history'  AND sc.name = N'emp_mgt_db'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [emp_mgt_db].[t_line_support_history]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[emp_mgt_db].[t_line_support_history]
(
   [id] int IDENTITY(13, 1)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [line_support_id] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [emp_no] nvarchar(255)  NOT NULL,
   [day] date  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [shift] nvarchar(5)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [line_no_from] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [line_no_to] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [set_by] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [set_by_no] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [set_status_by] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [set_status_by_no] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [status] nvarchar(255)  NOT NULL,
   [date_updated] datetime  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'emp_mgt_db.t_line_support_history',
        N'SCHEMA', N'emp_mgt_db',
        N'TABLE', N't_line_support_history'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N't_notif_line_support'  AND sc.name = N'emp_mgt_db'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N't_notif_line_support'  AND sc.name = N'emp_mgt_db'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [emp_mgt_db].[t_notif_line_support]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[emp_mgt_db].[t_notif_line_support]
(
   [id] int IDENTITY(18, 1)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [emp_no] nvarchar(255)  NOT NULL,
   [pending_ls] int  NOT NULL,
   [accepted_ls] int  NOT NULL,
   [rejected_ls] int  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'emp_mgt_db.t_notif_line_support',
        N'SCHEMA', N'emp_mgt_db',
        N'TABLE', N't_notif_line_support'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N't_shuttle_allocation'  AND sc.name = N'emp_mgt_db'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N't_shuttle_allocation'  AND sc.name = N'emp_mgt_db'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [emp_mgt_db].[t_shuttle_allocation]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[emp_mgt_db].[t_shuttle_allocation]
(
   [id] int IDENTITY(17, 1)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [emp_no] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [dept] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [section] nvarchar(255)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [line_no] nvarchar(255)  NOT NULL,
   [day] date  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [shift] nvarchar(5)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [shift_group] nvarchar(100)  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [shuttle_route] nvarchar(50)  NOT NULL,
   [out_5] int  NOT NULL,
   [out_6] int  NOT NULL,
   [out_7] int  NOT NULL,
   [out_8] int  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [set_by] nvarchar(255)  NOT NULL,
   [date_updated] datetime  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'emp_mgt_db.t_shuttle_allocation',
        N'SCHEMA', N'emp_mgt_db',
        N'TABLE', N't_shuttle_allocation'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N't_time_in_out'  AND sc.name = N'emp_mgt_db'  AND type in (N'U'))
BEGIN

  DECLARE @drop_statement nvarchar(500)

  DECLARE drop_cursor CURSOR FOR
      SELECT 'alter table '+quotename(schema_name(ob.schema_id))+
      '.'+quotename(object_name(ob.object_id))+ ' drop constraint ' + quotename(fk.name) 
      FROM sys.objects ob INNER JOIN sys.foreign_keys fk ON fk.parent_object_id = ob.object_id
      WHERE fk.referenced_object_id = 
          (
             SELECT so.object_id 
             FROM sys.objects so JOIN sys.schemas sc
             ON so.schema_id = sc.schema_id
             WHERE so.name = N't_time_in_out'  AND sc.name = N'emp_mgt_db'  AND type in (N'U')
           )

  OPEN drop_cursor

  FETCH NEXT FROM drop_cursor
  INTO @drop_statement

  WHILE @@FETCH_STATUS = 0
  BEGIN
     EXEC (@drop_statement)

     FETCH NEXT FROM drop_cursor
     INTO @drop_statement
  END

  CLOSE drop_cursor
  DEALLOCATE drop_cursor

  DROP TABLE [emp_mgt_db].[t_time_in_out]
END 
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE 
[emp_mgt_db].[t_time_in_out]
(
   [id] int IDENTITY(130, 1)  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [emp_no] nvarchar(255)  NOT NULL,
   [day] date  NOT NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [shift] nvarchar(5)  NOT NULL,
   [time_in] datetime  NOT NULL,
   [time_out] datetime  NULL,

   /*
   *   SSMA warning messages:
   *   M2SS0183: The following SQL clause was ignored during conversion: COLLATE utf8mb4_unicode_ci.
   */

   [ip] nvarchar(100)  NULL,
   [date_updated] datetime  NOT NULL
)
WITH (DATA_COMPRESSION = NONE)
GO
BEGIN TRY
    EXEC sp_addextendedproperty
        N'MS_SSMA_SOURCE', N'emp_mgt_db.t_time_in_out',
        N'SCHEMA', N'emp_mgt_db',
        N'TABLE', N't_time_in_out'
END TRY
BEGIN CATCH
    IF (@@TRANCOUNT > 0) ROLLBACK
    PRINT ERROR_MESSAGE()
END CATCH
GO

USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_m_access_locations_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'PK'))
ALTER TABLE [emp_mgt_db].[m_access_locations] DROP CONSTRAINT [PK_m_access_locations_id]
 GO



ALTER TABLE [emp_mgt_db].[m_access_locations]
 ADD CONSTRAINT [PK_m_access_locations_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_m_accounts_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'PK'))
ALTER TABLE [emp_mgt_db].[m_accounts] DROP CONSTRAINT [PK_m_accounts_id]
 GO



ALTER TABLE [emp_mgt_db].[m_accounts]
 ADD CONSTRAINT [PK_m_accounts_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_m_clinic_accounts_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'PK'))
ALTER TABLE [emp_mgt_db].[m_clinic_accounts] DROP CONSTRAINT [PK_m_clinic_accounts_id]
 GO



ALTER TABLE [emp_mgt_db].[m_clinic_accounts]
 ADD CONSTRAINT [PK_m_clinic_accounts_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_m_control_area_accounts_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'PK'))
ALTER TABLE [emp_mgt_db].[m_control_area_accounts] DROP CONSTRAINT [PK_m_control_area_accounts_id]
 GO



ALTER TABLE [emp_mgt_db].[m_control_area_accounts]
 ADD CONSTRAINT [PK_m_control_area_accounts_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_m_dept_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'PK'))
ALTER TABLE [emp_mgt_db].[m_dept] DROP CONSTRAINT [PK_m_dept_id]
 GO



ALTER TABLE [emp_mgt_db].[m_dept]
 ADD CONSTRAINT [PK_m_dept_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_m_employees_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'PK'))
ALTER TABLE [emp_mgt_db].[m_employees] DROP CONSTRAINT [PK_m_employees_id]
 GO



ALTER TABLE [emp_mgt_db].[m_employees]
 ADD CONSTRAINT [PK_m_employees_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_m_falp_groups_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'PK'))
ALTER TABLE [emp_mgt_db].[m_falp_groups] DROP CONSTRAINT [PK_m_falp_groups_id]
 GO



ALTER TABLE [emp_mgt_db].[m_falp_groups]
 ADD CONSTRAINT [PK_m_falp_groups_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_m_hr_accounts_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'PK'))
ALTER TABLE [emp_mgt_db].[m_hr_accounts] DROP CONSTRAINT [PK_m_hr_accounts_id]
 GO



ALTER TABLE [emp_mgt_db].[m_hr_accounts]
 ADD CONSTRAINT [PK_m_hr_accounts_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_m_positions_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'PK'))
ALTER TABLE [emp_mgt_db].[m_positions] DROP CONSTRAINT [PK_m_positions_id]
 GO



ALTER TABLE [emp_mgt_db].[m_positions]
 ADD CONSTRAINT [PK_m_positions_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_m_process_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'PK'))
ALTER TABLE [emp_mgt_db].[m_process] DROP CONSTRAINT [PK_m_process_id]
 GO



ALTER TABLE [emp_mgt_db].[m_process]
 ADD CONSTRAINT [PK_m_process_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_m_providers_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'PK'))
ALTER TABLE [emp_mgt_db].[m_providers] DROP CONSTRAINT [PK_m_providers_id]
 GO



ALTER TABLE [emp_mgt_db].[m_providers]
 ADD CONSTRAINT [PK_m_providers_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_m_shuttle_routes_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'PK'))
ALTER TABLE [emp_mgt_db].[m_shuttle_routes] DROP CONSTRAINT [PK_m_shuttle_routes_id]
 GO



ALTER TABLE [emp_mgt_db].[m_shuttle_routes]
 ADD CONSTRAINT [PK_m_shuttle_routes_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_m_sub_sections_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'PK'))
ALTER TABLE [emp_mgt_db].[m_sub_sections] DROP CONSTRAINT [PK_m_sub_sections_id]
 GO



ALTER TABLE [emp_mgt_db].[m_sub_sections]
 ADD CONSTRAINT [PK_m_sub_sections_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_t_absences_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'PK'))
ALTER TABLE [emp_mgt_db].[t_absences] DROP CONSTRAINT [PK_t_absences_id]
 GO



ALTER TABLE [emp_mgt_db].[t_absences]
 ADD CONSTRAINT [PK_t_absences_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_t_leave_form_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'PK'))
ALTER TABLE [emp_mgt_db].[t_leave_form] DROP CONSTRAINT [PK_t_leave_form_id]
 GO



ALTER TABLE [emp_mgt_db].[t_leave_form]
 ADD CONSTRAINT [PK_t_leave_form_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_t_leave_form_history_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'PK'))
ALTER TABLE [emp_mgt_db].[t_leave_form_history] DROP CONSTRAINT [PK_t_leave_form_history_id]
 GO



ALTER TABLE [emp_mgt_db].[t_leave_form_history]
 ADD CONSTRAINT [PK_t_leave_form_history_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_t_line_support_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'PK'))
ALTER TABLE [emp_mgt_db].[t_line_support] DROP CONSTRAINT [PK_t_line_support_id]
 GO



ALTER TABLE [emp_mgt_db].[t_line_support]
 ADD CONSTRAINT [PK_t_line_support_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_t_line_support_history_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'PK'))
ALTER TABLE [emp_mgt_db].[t_line_support_history] DROP CONSTRAINT [PK_t_line_support_history_id]
 GO



ALTER TABLE [emp_mgt_db].[t_line_support_history]
 ADD CONSTRAINT [PK_t_line_support_history_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_t_notif_line_support_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'PK'))
ALTER TABLE [emp_mgt_db].[t_notif_line_support] DROP CONSTRAINT [PK_t_notif_line_support_id]
 GO



ALTER TABLE [emp_mgt_db].[t_notif_line_support]
 ADD CONSTRAINT [PK_t_notif_line_support_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_t_shuttle_allocation_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'PK'))
ALTER TABLE [emp_mgt_db].[t_shuttle_allocation] DROP CONSTRAINT [PK_t_shuttle_allocation_id]
 GO



ALTER TABLE [emp_mgt_db].[t_shuttle_allocation]
 ADD CONSTRAINT [PK_t_shuttle_allocation_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'PK_t_time_in_out_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'PK'))
ALTER TABLE [emp_mgt_db].[t_time_in_out] DROP CONSTRAINT [PK_t_time_in_out_id]
 GO



ALTER TABLE [emp_mgt_db].[t_time_in_out]
 ADD CONSTRAINT [PK_t_time_in_out_id]
   PRIMARY KEY
   CLUSTERED ([id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_accounts$emp_no'  AND sc.name = N'emp_mgt_db'  AND type in (N'UQ'))
ALTER TABLE [emp_mgt_db].[m_accounts] DROP CONSTRAINT [m_accounts$emp_no]
 GO



ALTER TABLE [emp_mgt_db].[m_accounts]
 ADD CONSTRAINT [m_accounts$emp_no]
 UNIQUE 
   NONCLUSTERED ([emp_no] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_clinic_accounts$emp_no'  AND sc.name = N'emp_mgt_db'  AND type in (N'UQ'))
ALTER TABLE [emp_mgt_db].[m_clinic_accounts] DROP CONSTRAINT [m_clinic_accounts$emp_no]
 GO



ALTER TABLE [emp_mgt_db].[m_clinic_accounts]
 ADD CONSTRAINT [m_clinic_accounts$emp_no]
 UNIQUE 
   NONCLUSTERED ([emp_no] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_control_area_accounts$emp_no'  AND sc.name = N'emp_mgt_db'  AND type in (N'UQ'))
ALTER TABLE [emp_mgt_db].[m_control_area_accounts] DROP CONSTRAINT [m_control_area_accounts$emp_no]
 GO



ALTER TABLE [emp_mgt_db].[m_control_area_accounts]
 ADD CONSTRAINT [m_control_area_accounts$emp_no]
 UNIQUE 
   NONCLUSTERED ([emp_no] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_dept$dept'  AND sc.name = N'emp_mgt_db'  AND type in (N'UQ'))
ALTER TABLE [emp_mgt_db].[m_dept] DROP CONSTRAINT [m_dept$dept]
 GO



ALTER TABLE [emp_mgt_db].[m_dept]
 ADD CONSTRAINT [m_dept$dept]
 UNIQUE 
   NONCLUSTERED ([dept] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_employees$emp_no'  AND sc.name = N'emp_mgt_db'  AND type in (N'UQ'))
ALTER TABLE [emp_mgt_db].[m_employees] DROP CONSTRAINT [m_employees$emp_no]
 GO



ALTER TABLE [emp_mgt_db].[m_employees]
 ADD CONSTRAINT [m_employees$emp_no]
 UNIQUE 
   NONCLUSTERED ([emp_no] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_falp_groups$falp_group'  AND sc.name = N'emp_mgt_db'  AND type in (N'UQ'))
ALTER TABLE [emp_mgt_db].[m_falp_groups] DROP CONSTRAINT [m_falp_groups$falp_group]
 GO



ALTER TABLE [emp_mgt_db].[m_falp_groups]
 ADD CONSTRAINT [m_falp_groups$falp_group]
 UNIQUE 
   NONCLUSTERED ([falp_group] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_hr_accounts$emp_no'  AND sc.name = N'emp_mgt_db'  AND type in (N'UQ'))
ALTER TABLE [emp_mgt_db].[m_hr_accounts] DROP CONSTRAINT [m_hr_accounts$emp_no]
 GO



ALTER TABLE [emp_mgt_db].[m_hr_accounts]
 ADD CONSTRAINT [m_hr_accounts$emp_no]
 UNIQUE 
   NONCLUSTERED ([emp_no] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_positions$provider'  AND sc.name = N'emp_mgt_db'  AND type in (N'UQ'))
ALTER TABLE [emp_mgt_db].[m_positions] DROP CONSTRAINT [m_positions$provider]
 GO



ALTER TABLE [emp_mgt_db].[m_positions]
 ADD CONSTRAINT [m_positions$provider]
 UNIQUE 
   NONCLUSTERED ([position] ASC)

GO

IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_positions$position'  AND sc.name = N'emp_mgt_db'  AND type in (N'UQ'))
ALTER TABLE [emp_mgt_db].[m_positions] DROP CONSTRAINT [m_positions$position]
 GO



ALTER TABLE [emp_mgt_db].[m_positions]
 ADD CONSTRAINT [m_positions$position]
 UNIQUE 
   NONCLUSTERED ([position] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_process$process'  AND sc.name = N'emp_mgt_db'  AND type in (N'UQ'))
ALTER TABLE [emp_mgt_db].[m_process] DROP CONSTRAINT [m_process$process]
 GO



ALTER TABLE [emp_mgt_db].[m_process]
 ADD CONSTRAINT [m_process$process]
 UNIQUE 
   NONCLUSTERED ([process] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_providers$provider'  AND sc.name = N'emp_mgt_db'  AND type in (N'UQ'))
ALTER TABLE [emp_mgt_db].[m_providers] DROP CONSTRAINT [m_providers$provider]
 GO



ALTER TABLE [emp_mgt_db].[m_providers]
 ADD CONSTRAINT [m_providers$provider]
 UNIQUE 
   NONCLUSTERED ([provider] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_shuttle_routes$shuttle_route'  AND sc.name = N'emp_mgt_db'  AND type in (N'UQ'))
ALTER TABLE [emp_mgt_db].[m_shuttle_routes] DROP CONSTRAINT [m_shuttle_routes$shuttle_route]
 GO



ALTER TABLE [emp_mgt_db].[m_shuttle_routes]
 ADD CONSTRAINT [m_shuttle_routes$shuttle_route]
 UNIQUE 
   NONCLUSTERED ([shuttle_route] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N'm_sub_sections$sub_section'  AND sc.name = N'emp_mgt_db'  AND type in (N'UQ'))
ALTER TABLE [emp_mgt_db].[m_sub_sections] DROP CONSTRAINT [m_sub_sections$sub_section]
 GO



ALTER TABLE [emp_mgt_db].[m_sub_sections]
 ADD CONSTRAINT [m_sub_sections$sub_section]
 UNIQUE 
   NONCLUSTERED ([sub_section] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N't_leave_form$leave_form_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'UQ'))
ALTER TABLE [emp_mgt_db].[t_leave_form] DROP CONSTRAINT [t_leave_form$leave_form_id]
 GO



ALTER TABLE [emp_mgt_db].[t_leave_form]
 ADD CONSTRAINT [t_leave_form$leave_form_id]
 UNIQUE 
   NONCLUSTERED ([leave_form_id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N't_leave_form_history$leave_form_id'  AND sc.name = N'emp_mgt_db'  AND type in (N'UQ'))
ALTER TABLE [emp_mgt_db].[t_leave_form_history] DROP CONSTRAINT [t_leave_form_history$leave_form_id]
 GO



ALTER TABLE [emp_mgt_db].[t_leave_form_history]
 ADD CONSTRAINT [t_leave_form_history$leave_form_id]
 UNIQUE 
   NONCLUSTERED ([leave_form_id] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (SELECT * FROM sys.objects so JOIN sys.schemas sc ON so.schema_id = sc.schema_id WHERE so.name = N't_notif_line_support$emp_no'  AND sc.name = N'emp_mgt_db'  AND type in (N'UQ'))
ALTER TABLE [emp_mgt_db].[t_notif_line_support] DROP CONSTRAINT [t_notif_line_support$emp_no]
 GO



ALTER TABLE [emp_mgt_db].[t_notif_line_support]
 ADD CONSTRAINT [t_notif_line_support$emp_no]
 UNIQUE 
   NONCLUSTERED ([emp_no] ASC)

GO


USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N't_time_in_out'  AND sc.name = N'emp_mgt_db'  AND si.name = N'day' AND so.type in (N'U'))
   DROP INDEX [day] ON [emp_mgt_db].[t_time_in_out] 
GO
CREATE NONCLUSTERED INDEX [day] ON [emp_mgt_db].[t_time_in_out]
(
   [day] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N't_line_support'  AND sc.name = N'emp_mgt_db'  AND si.name = N'day' AND so.type in (N'U'))
   DROP INDEX [day] ON [emp_mgt_db].[t_line_support] 
GO
CREATE NONCLUSTERED INDEX [day] ON [emp_mgt_db].[t_line_support]
(
   [day] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N't_shuttle_allocation'  AND sc.name = N'emp_mgt_db'  AND si.name = N'day' AND so.type in (N'U'))
   DROP INDEX [day] ON [emp_mgt_db].[t_shuttle_allocation] 
GO
CREATE NONCLUSTERED INDEX [day] ON [emp_mgt_db].[t_shuttle_allocation]
(
   [day] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N't_line_support_history'  AND sc.name = N'emp_mgt_db'  AND si.name = N'day' AND so.type in (N'U'))
   DROP INDEX [day] ON [emp_mgt_db].[t_line_support_history] 
GO
CREATE NONCLUSTERED INDEX [day] ON [emp_mgt_db].[t_line_support_history]
(
   [day] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N't_absences'  AND sc.name = N'emp_mgt_db'  AND si.name = N'day' AND so.type in (N'U'))
   DROP INDEX [day] ON [emp_mgt_db].[t_absences] 
GO
CREATE NONCLUSTERED INDEX [day] ON [emp_mgt_db].[t_absences]
(
   [day] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N't_leave_form'  AND sc.name = N'emp_mgt_db'  AND si.name = N'emp_no' AND so.type in (N'U'))
   DROP INDEX [emp_no] ON [emp_mgt_db].[t_leave_form] 
GO
CREATE NONCLUSTERED INDEX [emp_no] ON [emp_mgt_db].[t_leave_form]
(
   [emp_no] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N't_absences'  AND sc.name = N'emp_mgt_db'  AND si.name = N'emp_no' AND so.type in (N'U'))
   DROP INDEX [emp_no] ON [emp_mgt_db].[t_absences] 
GO
CREATE NONCLUSTERED INDEX [emp_no] ON [emp_mgt_db].[t_absences]
(
   [emp_no] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N't_shuttle_allocation'  AND sc.name = N'emp_mgt_db'  AND si.name = N'emp_no' AND so.type in (N'U'))
   DROP INDEX [emp_no] ON [emp_mgt_db].[t_shuttle_allocation] 
GO
CREATE NONCLUSTERED INDEX [emp_no] ON [emp_mgt_db].[t_shuttle_allocation]
(
   [emp_no] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N't_line_support'  AND sc.name = N'emp_mgt_db'  AND si.name = N'emp_no' AND so.type in (N'U'))
   DROP INDEX [emp_no] ON [emp_mgt_db].[t_line_support] 
GO
CREATE NONCLUSTERED INDEX [emp_no] ON [emp_mgt_db].[t_line_support]
(
   [emp_no] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N't_leave_form_history'  AND sc.name = N'emp_mgt_db'  AND si.name = N'emp_no' AND so.type in (N'U'))
   DROP INDEX [emp_no] ON [emp_mgt_db].[t_leave_form_history] 
GO
CREATE NONCLUSTERED INDEX [emp_no] ON [emp_mgt_db].[t_leave_form_history]
(
   [emp_no] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N't_line_support_history'  AND sc.name = N'emp_mgt_db'  AND si.name = N'emp_no' AND so.type in (N'U'))
   DROP INDEX [emp_no] ON [emp_mgt_db].[t_line_support_history] 
GO
CREATE NONCLUSTERED INDEX [emp_no] ON [emp_mgt_db].[t_line_support_history]
(
   [emp_no] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N't_time_in_out'  AND sc.name = N'emp_mgt_db'  AND si.name = N'emp_no' AND so.type in (N'U'))
   DROP INDEX [emp_no] ON [emp_mgt_db].[t_time_in_out] 
GO
CREATE NONCLUSTERED INDEX [emp_no] ON [emp_mgt_db].[t_time_in_out]
(
   [emp_no] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N'm_access_locations'  AND sc.name = N'emp_mgt_db'  AND si.name = N'ip' AND so.type in (N'U'))
   DROP INDEX [ip] ON [emp_mgt_db].[m_access_locations] 
GO
CREATE NONCLUSTERED INDEX [ip] ON [emp_mgt_db].[m_access_locations]
(
   [ip] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N't_time_in_out'  AND sc.name = N'emp_mgt_db'  AND si.name = N'ip' AND so.type in (N'U'))
   DROP INDEX [ip] ON [emp_mgt_db].[t_time_in_out] 
GO
CREATE NONCLUSTERED INDEX [ip] ON [emp_mgt_db].[t_time_in_out]
(
   [ip] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N't_line_support'  AND sc.name = N'emp_mgt_db'  AND si.name = N'line_support_id' AND so.type in (N'U'))
   DROP INDEX [line_support_id] ON [emp_mgt_db].[t_line_support] 
GO
CREATE NONCLUSTERED INDEX [line_support_id] ON [emp_mgt_db].[t_line_support]
(
   [line_support_id] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N't_line_support_history'  AND sc.name = N'emp_mgt_db'  AND si.name = N'line_support_id' AND so.type in (N'U'))
   DROP INDEX [line_support_id] ON [emp_mgt_db].[t_line_support_history] 
GO
CREATE NONCLUSTERED INDEX [line_support_id] ON [emp_mgt_db].[t_line_support_history]
(
   [line_support_id] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N't_line_support'  AND sc.name = N'emp_mgt_db'  AND si.name = N'shift' AND so.type in (N'U'))
   DROP INDEX [shift] ON [emp_mgt_db].[t_line_support] 
GO
CREATE NONCLUSTERED INDEX [shift] ON [emp_mgt_db].[t_line_support]
(
   [shift] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N't_shuttle_allocation'  AND sc.name = N'emp_mgt_db'  AND si.name = N'shift' AND so.type in (N'U'))
   DROP INDEX [shift] ON [emp_mgt_db].[t_shuttle_allocation] 
GO
CREATE NONCLUSTERED INDEX [shift] ON [emp_mgt_db].[t_shuttle_allocation]
(
   [shift] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N't_time_in_out'  AND sc.name = N'emp_mgt_db'  AND si.name = N'shift' AND so.type in (N'U'))
   DROP INDEX [shift] ON [emp_mgt_db].[t_time_in_out] 
GO
CREATE NONCLUSTERED INDEX [shift] ON [emp_mgt_db].[t_time_in_out]
(
   [shift] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N't_line_support_history'  AND sc.name = N'emp_mgt_db'  AND si.name = N'shift' AND so.type in (N'U'))
   DROP INDEX [shift] ON [emp_mgt_db].[t_line_support_history] 
GO
CREATE NONCLUSTERED INDEX [shift] ON [emp_mgt_db].[t_line_support_history]
(
   [shift] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N't_absences'  AND sc.name = N'emp_mgt_db'  AND si.name = N'shift_group' AND so.type in (N'U'))
   DROP INDEX [shift_group] ON [emp_mgt_db].[t_absences] 
GO
CREATE NONCLUSTERED INDEX [shift_group] ON [emp_mgt_db].[t_absences]
(
   [shift_group] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
IF EXISTS (
       SELECT * FROM sys.objects  so JOIN sys.indexes si
       ON so.object_id = si.object_id
       JOIN sys.schemas sc
       ON so.schema_id = sc.schema_id
       WHERE so.name = N't_shuttle_allocation'  AND sc.name = N'emp_mgt_db'  AND si.name = N'shift_group' AND so.type in (N'U'))
   DROP INDEX [shift_group] ON [emp_mgt_db].[t_shuttle_allocation] 
GO
CREATE NONCLUSTERED INDEX [shift_group] ON [emp_mgt_db].[t_shuttle_allocation]
(
   [shift_group] ASC
)
WITH (SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, IGNORE_DUP_KEY = OFF, ONLINE = OFF) ON [PRIMARY] 
GO
GO

USE emp_mgt_db
GO
ALTER TABLE  [emp_mgt_db].[m_access_locations]
 ADD DEFAULT NULL FOR [dept]
GO

ALTER TABLE  [emp_mgt_db].[m_access_locations]
 ADD DEFAULT NULL FOR [section]
GO

ALTER TABLE  [emp_mgt_db].[m_access_locations]
 ADD DEFAULT NULL FOR [line_no]
GO

ALTER TABLE  [emp_mgt_db].[m_access_locations]
 ADD DEFAULT NULL FOR [ip]
GO

ALTER TABLE  [emp_mgt_db].[m_access_locations]
 ADD DEFAULT getdate() FOR [date_updated]
GO


USE emp_mgt_db
GO
ALTER TABLE  [emp_mgt_db].[m_accounts]
 ADD DEFAULT NULL FOR [section]
GO

ALTER TABLE  [emp_mgt_db].[m_accounts]
 ADD DEFAULT NULL FOR [line_no]
GO

ALTER TABLE  [emp_mgt_db].[m_accounts]
 ADD DEFAULT NULL FOR [shift_group]
GO

ALTER TABLE  [emp_mgt_db].[m_accounts]
 ADD DEFAULT getdate() FOR [date_updated]
GO


USE emp_mgt_db
GO
ALTER TABLE  [emp_mgt_db].[m_clinic_accounts]
 ADD DEFAULT getdate() FOR [date_updated]
GO


USE emp_mgt_db
GO
ALTER TABLE  [emp_mgt_db].[m_control_area_accounts]
 ADD DEFAULT NULL FOR [section]
GO

ALTER TABLE  [emp_mgt_db].[m_control_area_accounts]
 ADD DEFAULT NULL FOR [line_no]
GO

ALTER TABLE  [emp_mgt_db].[m_control_area_accounts]
 ADD DEFAULT NULL FOR [shift_group]
GO

ALTER TABLE  [emp_mgt_db].[m_control_area_accounts]
 ADD DEFAULT getdate() FOR [date_updated]
GO


USE emp_mgt_db
GO
ALTER TABLE  [emp_mgt_db].[m_dept]
 ADD DEFAULT getdate() FOR [date_updated]
GO


USE emp_mgt_db
GO
ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [full_name]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [dept]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [section]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [sub_section]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [process]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [line_no]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [position]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [provider]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [gender]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [shift_group]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [date_hired]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [address]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [contact_no]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [emp_status]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [shuttle_route]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [emp_js_s]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [emp_js_s_no]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [emp_sv]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [emp_sv_no]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [emp_approver]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [emp_approver_no]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT 0 FOR [resigned]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT NULL FOR [resigned_date]
GO

ALTER TABLE  [emp_mgt_db].[m_employees]
 ADD DEFAULT getdate() FOR [date_updated]
GO


USE emp_mgt_db
GO
ALTER TABLE  [emp_mgt_db].[m_falp_groups]
 ADD DEFAULT getdate() FOR [date_updated]
GO


USE emp_mgt_db
GO
ALTER TABLE  [emp_mgt_db].[m_hr_accounts]
 ADD DEFAULT getdate() FOR [date_updated]
GO


USE emp_mgt_db
GO
ALTER TABLE  [emp_mgt_db].[m_positions]
 ADD DEFAULT getdate() FOR [date_updated]
GO


USE emp_mgt_db
GO
ALTER TABLE  [emp_mgt_db].[m_process]
 ADD DEFAULT getdate() FOR [date_updated]
GO


USE emp_mgt_db
GO
ALTER TABLE  [emp_mgt_db].[m_providers]
 ADD DEFAULT getdate() FOR [date_updated]
GO


USE emp_mgt_db
GO
ALTER TABLE  [emp_mgt_db].[m_shuttle_routes]
 ADD DEFAULT getdate() FOR [date_updated]
GO


USE emp_mgt_db
GO
ALTER TABLE  [emp_mgt_db].[m_sub_sections]
 ADD DEFAULT getdate() FOR [date_updated]
GO


USE emp_mgt_db
GO
ALTER TABLE  [emp_mgt_db].[t_absences]
 ADD DEFAULT getdate() FOR [day]
GO

ALTER TABLE  [emp_mgt_db].[t_absences]
 ADD DEFAULT NULL FOR [shift_group]
GO

ALTER TABLE  [emp_mgt_db].[t_absences]
 ADD DEFAULT getdate() FOR [date_updated]
GO


USE emp_mgt_db
GO
ALTER TABLE  [emp_mgt_db].[t_leave_form]
 ADD DEFAULT getdate() FOR [date_filed]
GO

ALTER TABLE  [emp_mgt_db].[t_leave_form]
 ADD DEFAULT 1 FOR [total_leave_days]
GO

ALTER TABLE  [emp_mgt_db].[t_leave_form]
 ADD DEFAULT N'pending' FOR [leave_form_status]
GO

ALTER TABLE  [emp_mgt_db].[t_leave_form]
 ADD DEFAULT NULL FOR [sl_r1_1_date]
GO

ALTER TABLE  [emp_mgt_db].[t_leave_form]
 ADD DEFAULT NULL FOR [sl_r1_1_time_in]
GO

ALTER TABLE  [emp_mgt_db].[t_leave_form]
 ADD DEFAULT NULL FOR [sl_r1_1_time_out]
GO

ALTER TABLE  [emp_mgt_db].[t_leave_form]
 ADD DEFAULT NULL FOR [sl_r1_3_date]
GO

ALTER TABLE  [emp_mgt_db].[t_leave_form]
 ADD DEFAULT NULL FOR [sl_rc_2_from]
GO

ALTER TABLE  [emp_mgt_db].[t_leave_form]
 ADD DEFAULT NULL FOR [sl_rc_2_to]
GO

ALTER TABLE  [emp_mgt_db].[t_leave_form]
 ADD DEFAULT NULL FOR [sl_dr_date]
GO

ALTER TABLE  [emp_mgt_db].[t_leave_form]
 ADD DEFAULT getdate() FOR [date_updated]
GO


USE emp_mgt_db
GO
ALTER TABLE  [emp_mgt_db].[t_leave_form_history]
 ADD DEFAULT getdate() FOR [date_filed]
GO

ALTER TABLE  [emp_mgt_db].[t_leave_form_history]
 ADD DEFAULT 1 FOR [total_leave_days]
GO

ALTER TABLE  [emp_mgt_db].[t_leave_form_history]
 ADD DEFAULT N'pending' FOR [leave_form_status]
GO

ALTER TABLE  [emp_mgt_db].[t_leave_form_history]
 ADD DEFAULT NULL FOR [sl_r1_1_date]
GO

ALTER TABLE  [emp_mgt_db].[t_leave_form_history]
 ADD DEFAULT NULL FOR [sl_r1_1_time_in]
GO

ALTER TABLE  [emp_mgt_db].[t_leave_form_history]
 ADD DEFAULT NULL FOR [sl_r1_1_time_out]
GO

ALTER TABLE  [emp_mgt_db].[t_leave_form_history]
 ADD DEFAULT NULL FOR [sl_r1_3_date]
GO

ALTER TABLE  [emp_mgt_db].[t_leave_form_history]
 ADD DEFAULT NULL FOR [sl_rc_2_from]
GO

ALTER TABLE  [emp_mgt_db].[t_leave_form_history]
 ADD DEFAULT NULL FOR [sl_rc_2_to]
GO

ALTER TABLE  [emp_mgt_db].[t_leave_form_history]
 ADD DEFAULT NULL FOR [sl_dr_date]
GO

ALTER TABLE  [emp_mgt_db].[t_leave_form_history]
 ADD DEFAULT getdate() FOR [date_updated]
GO


USE emp_mgt_db
GO
ALTER TABLE  [emp_mgt_db].[t_line_support]
 ADD DEFAULT getdate() FOR [day]
GO

ALTER TABLE  [emp_mgt_db].[t_line_support]
 ADD DEFAULT N'added' FOR [status]
GO

ALTER TABLE  [emp_mgt_db].[t_line_support]
 ADD DEFAULT getdate() FOR [date_updated]
GO


USE emp_mgt_db
GO
ALTER TABLE  [emp_mgt_db].[t_line_support_history]
 ADD DEFAULT getdate() FOR [day]
GO

ALTER TABLE  [emp_mgt_db].[t_line_support_history]
 ADD DEFAULT N'added' FOR [status]
GO

ALTER TABLE  [emp_mgt_db].[t_line_support_history]
 ADD DEFAULT getdate() FOR [date_updated]
GO


USE emp_mgt_db
GO
ALTER TABLE  [emp_mgt_db].[t_notif_line_support]
 ADD DEFAULT 0 FOR [pending_ls]
GO

ALTER TABLE  [emp_mgt_db].[t_notif_line_support]
 ADD DEFAULT 0 FOR [accepted_ls]
GO

ALTER TABLE  [emp_mgt_db].[t_notif_line_support]
 ADD DEFAULT 0 FOR [rejected_ls]
GO


USE emp_mgt_db
GO
ALTER TABLE  [emp_mgt_db].[t_shuttle_allocation]
 ADD DEFAULT NULL FOR [shift_group]
GO

ALTER TABLE  [emp_mgt_db].[t_shuttle_allocation]
 ADD DEFAULT 0 FOR [out_5]
GO

ALTER TABLE  [emp_mgt_db].[t_shuttle_allocation]
 ADD DEFAULT 0 FOR [out_6]
GO

ALTER TABLE  [emp_mgt_db].[t_shuttle_allocation]
 ADD DEFAULT 0 FOR [out_7]
GO

ALTER TABLE  [emp_mgt_db].[t_shuttle_allocation]
 ADD DEFAULT 0 FOR [out_8]
GO

ALTER TABLE  [emp_mgt_db].[t_shuttle_allocation]
 ADD DEFAULT getdate() FOR [date_updated]
GO


USE emp_mgt_db
GO
ALTER TABLE  [emp_mgt_db].[t_time_in_out]
 ADD DEFAULT getdate() FOR [day]
GO

ALTER TABLE  [emp_mgt_db].[t_time_in_out]
 ADD DEFAULT getdate() FOR [time_in]
GO

ALTER TABLE  [emp_mgt_db].[t_time_in_out]
 ADD DEFAULT NULL FOR [time_out]
GO

ALTER TABLE  [emp_mgt_db].[t_time_in_out]
 ADD DEFAULT NULL FOR [ip]
GO

ALTER TABLE  [emp_mgt_db].[t_time_in_out]
 ADD DEFAULT getdate() FOR [date_updated]
GO

