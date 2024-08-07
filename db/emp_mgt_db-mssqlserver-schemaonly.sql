USE [emp_mgt_db]
GO
ALTER TABLE [dbo].[t_time_in_out] DROP CONSTRAINT [DF__t_time_in__date___60083D91]
GO
ALTER TABLE [dbo].[t_time_in_out] DROP CONSTRAINT [DF__t_time_in_ou__ip__5F141958]
GO
ALTER TABLE [dbo].[t_time_in_out] DROP CONSTRAINT [DF__t_time_in__time___5E1FF51F]
GO
ALTER TABLE [dbo].[t_time_in_out] DROP CONSTRAINT [DF__t_time_in__time___5D2BD0E6]
GO
ALTER TABLE [dbo].[t_time_in_out] DROP CONSTRAINT [DF__t_time_in_o__day__5C37ACAD]
GO
ALTER TABLE [dbo].[t_shuttle_allocation] DROP CONSTRAINT [DF__t_shuttle__date___5B438874]
GO
ALTER TABLE [dbo].[t_shuttle_allocation] DROP CONSTRAINT [DF__t_shuttle__out_8__5A4F643B]
GO
ALTER TABLE [dbo].[t_shuttle_allocation] DROP CONSTRAINT [DF__t_shuttle__out_7__595B4002]
GO
ALTER TABLE [dbo].[t_shuttle_allocation] DROP CONSTRAINT [DF__t_shuttle__out_6__58671BC9]
GO
ALTER TABLE [dbo].[t_shuttle_allocation] DROP CONSTRAINT [DF__t_shuttle__out_5__5772F790]
GO
ALTER TABLE [dbo].[t_shuttle_allocation] DROP CONSTRAINT [DF__t_shuttle__shift__567ED357]
GO
ALTER TABLE [dbo].[t_notif_line_support] DROP CONSTRAINT [DF__t_notif_l__rejec__558AAF1E]
GO
ALTER TABLE [dbo].[t_notif_line_support] DROP CONSTRAINT [DF__t_notif_l__accep__54968AE5]
GO
ALTER TABLE [dbo].[t_notif_line_support] DROP CONSTRAINT [DF__t_notif_l__pendi__53A266AC]
GO
ALTER TABLE [dbo].[t_line_support_history] DROP CONSTRAINT [DF__t_line_su__date___52AE4273]
GO
ALTER TABLE [dbo].[t_line_support_history] DROP CONSTRAINT [DF__t_line_su__statu__51BA1E3A]
GO
ALTER TABLE [dbo].[t_line_support_history] DROP CONSTRAINT [DF__t_line_supp__day__50C5FA01]
GO
ALTER TABLE [dbo].[t_line_support] DROP CONSTRAINT [DF__t_line_su__date___4FD1D5C8]
GO
ALTER TABLE [dbo].[t_line_support] DROP CONSTRAINT [DF__t_line_su__statu__4EDDB18F]
GO
ALTER TABLE [dbo].[t_line_support] DROP CONSTRAINT [DF__t_line_supp__day__4DE98D56]
GO
ALTER TABLE [dbo].[t_leave_form_history] DROP CONSTRAINT [DF__t_leave_f__date___4CF5691D]
GO
ALTER TABLE [dbo].[t_leave_form_history] DROP CONSTRAINT [DF__t_leave_f__sl_dr__4C0144E4]
GO
ALTER TABLE [dbo].[t_leave_form_history] DROP CONSTRAINT [DF__t_leave_f__sl_rc__4B0D20AB]
GO
ALTER TABLE [dbo].[t_leave_form_history] DROP CONSTRAINT [DF__t_leave_f__sl_rc__4A18FC72]
GO
ALTER TABLE [dbo].[t_leave_form_history] DROP CONSTRAINT [DF__t_leave_f__sl_r1__4924D839]
GO
ALTER TABLE [dbo].[t_leave_form_history] DROP CONSTRAINT [DF__t_leave_f__sl_r1__4830B400]
GO
ALTER TABLE [dbo].[t_leave_form_history] DROP CONSTRAINT [DF__t_leave_f__sl_r1__473C8FC7]
GO
ALTER TABLE [dbo].[t_leave_form_history] DROP CONSTRAINT [DF__t_leave_f__sl_r1__46486B8E]
GO
ALTER TABLE [dbo].[t_leave_form_history] DROP CONSTRAINT [DF__t_leave_f__leave__45544755]
GO
ALTER TABLE [dbo].[t_leave_form_history] DROP CONSTRAINT [DF__t_leave_f__total__4460231C]
GO
ALTER TABLE [dbo].[t_leave_form_history] DROP CONSTRAINT [DF__t_leave_f__date___436BFEE3]
GO
ALTER TABLE [dbo].[t_leave_form] DROP CONSTRAINT [DF__t_leave_f__date___4277DAAA]
GO
ALTER TABLE [dbo].[t_leave_form] DROP CONSTRAINT [DF__t_leave_f__sl_dr__4183B671]
GO
ALTER TABLE [dbo].[t_leave_form] DROP CONSTRAINT [DF__t_leave_f__sl_rc__408F9238]
GO
ALTER TABLE [dbo].[t_leave_form] DROP CONSTRAINT [DF__t_leave_f__sl_rc__3F9B6DFF]
GO
ALTER TABLE [dbo].[t_leave_form] DROP CONSTRAINT [DF__t_leave_f__sl_r1__3EA749C6]
GO
ALTER TABLE [dbo].[t_leave_form] DROP CONSTRAINT [DF__t_leave_f__sl_r1__3DB3258D]
GO
ALTER TABLE [dbo].[t_leave_form] DROP CONSTRAINT [DF__t_leave_f__sl_r1__3CBF0154]
GO
ALTER TABLE [dbo].[t_leave_form] DROP CONSTRAINT [DF__t_leave_f__sl_r1__3BCADD1B]
GO
ALTER TABLE [dbo].[t_leave_form] DROP CONSTRAINT [DF__t_leave_f__leave__3AD6B8E2]
GO
ALTER TABLE [dbo].[t_leave_form] DROP CONSTRAINT [DF__t_leave_f__total__39E294A9]
GO
ALTER TABLE [dbo].[t_leave_form] DROP CONSTRAINT [DF__t_leave_f__date___38EE7070]
GO
ALTER TABLE [dbo].[t_absences] DROP CONSTRAINT [DF__t_absence__date___37FA4C37]
GO
ALTER TABLE [dbo].[t_absences] DROP CONSTRAINT [DF__t_absence__shift__370627FE]
GO
ALTER TABLE [dbo].[t_absences] DROP CONSTRAINT [DF__t_absences__day__361203C5]
GO
ALTER TABLE [dbo].[m_sub_sections] DROP CONSTRAINT [DF__m_sub_sec__date___351DDF8C]
GO
ALTER TABLE [dbo].[m_shuttle_routes] DROP CONSTRAINT [DF__m_shuttle__date___3429BB53]
GO
ALTER TABLE [dbo].[m_providers] DROP CONSTRAINT [DF__m_provide__date___3335971A]
GO
ALTER TABLE [dbo].[m_process] DROP CONSTRAINT [DF__m_process__date___324172E1]
GO
ALTER TABLE [dbo].[m_positions] DROP CONSTRAINT [DF__m_positio__date___314D4EA8]
GO
ALTER TABLE [dbo].[m_hr_accounts] DROP CONSTRAINT [DF__m_hr_acco__date___30592A6F]
GO
ALTER TABLE [dbo].[m_falp_groups] DROP CONSTRAINT [DF__m_falp_gr__date___2F650636]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__date___2E70E1FD]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__resig__2D7CBDC4]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__resig__2C88998B]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__emp_a__2B947552]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__emp_a__2AA05119]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__emp_s__29AC2CE0]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__emp_s__28B808A7]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__emp_j__27C3E46E]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__emp_j__26CFC035]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__shutt__25DB9BFC]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__emp_s__24E777C3]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__conta__23F3538A]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__addre__22FF2F51]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__date___220B0B18]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__shift__2116E6DF]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__gende__2022C2A6]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__provi__1F2E9E6D]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__posit__1E3A7A34]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__line___1D4655FB]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__proce__1C5231C2]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__sub_s__1B5E0D89]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__secti__1A69E950]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employee__dept__1975C517]
GO
ALTER TABLE [dbo].[m_employees] DROP CONSTRAINT [DF__m_employe__full___1881A0DE]
GO
ALTER TABLE [dbo].[m_dept] DROP CONSTRAINT [DF__m_dept__date_upd__178D7CA5]
GO
ALTER TABLE [dbo].[m_control_area_accounts] DROP CONSTRAINT [DF__m_control__date___1699586C]
GO
ALTER TABLE [dbo].[m_control_area_accounts] DROP CONSTRAINT [DF__m_control__shift__15A53433]
GO
ALTER TABLE [dbo].[m_control_area_accounts] DROP CONSTRAINT [DF__m_control__line___14B10FFA]
GO
ALTER TABLE [dbo].[m_control_area_accounts] DROP CONSTRAINT [DF__m_control__secti__13BCEBC1]
GO
ALTER TABLE [dbo].[m_clinic_accounts] DROP CONSTRAINT [DF__m_clinic___date___12C8C788]
GO
ALTER TABLE [dbo].[m_accounts] DROP CONSTRAINT [DF__m_account__date___11D4A34F]
GO
ALTER TABLE [dbo].[m_accounts] DROP CONSTRAINT [DF__m_account__shift__10E07F16]
GO
ALTER TABLE [dbo].[m_accounts] DROP CONSTRAINT [DF__m_account__line___0FEC5ADD]
GO
ALTER TABLE [dbo].[m_accounts] DROP CONSTRAINT [DF__m_account__secti__0EF836A4]
GO
ALTER TABLE [dbo].[m_access_locations] DROP CONSTRAINT [DF__m_access___date___0E04126B]
GO
ALTER TABLE [dbo].[m_access_locations] DROP CONSTRAINT [DF__m_access_loc__ip__0D0FEE32]
GO
ALTER TABLE [dbo].[m_access_locations] DROP CONSTRAINT [DF__m_access___line___0C1BC9F9]
GO
ALTER TABLE [dbo].[m_access_locations] DROP CONSTRAINT [DF__m_access___secti__0B27A5C0]
GO
ALTER TABLE [dbo].[m_access_locations] DROP CONSTRAINT [DF__m_access_l__dept__0A338187]
GO
/****** Object:  Index [shift]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [shift] ON [dbo].[t_time_in_out]
GO
/****** Object:  Index [ip]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [ip] ON [dbo].[t_time_in_out]
GO
/****** Object:  Index [emp_no]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [emp_no] ON [dbo].[t_time_in_out]
GO
/****** Object:  Index [day]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [day] ON [dbo].[t_time_in_out]
GO
/****** Object:  Index [shift_group]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [shift_group] ON [dbo].[t_shuttle_allocation]
GO
/****** Object:  Index [shift]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [shift] ON [dbo].[t_shuttle_allocation]
GO
/****** Object:  Index [emp_no]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [emp_no] ON [dbo].[t_shuttle_allocation]
GO
/****** Object:  Index [day]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [day] ON [dbo].[t_shuttle_allocation]
GO
/****** Object:  Index [shift]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [shift] ON [dbo].[t_line_support_history]
GO
/****** Object:  Index [line_support_id]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [line_support_id] ON [dbo].[t_line_support_history]
GO
/****** Object:  Index [emp_no]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [emp_no] ON [dbo].[t_line_support_history]
GO
/****** Object:  Index [day]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [day] ON [dbo].[t_line_support_history]
GO
/****** Object:  Index [shift]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [shift] ON [dbo].[t_line_support]
GO
/****** Object:  Index [line_support_id]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [line_support_id] ON [dbo].[t_line_support]
GO
/****** Object:  Index [emp_no]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [emp_no] ON [dbo].[t_line_support]
GO
/****** Object:  Index [day]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [day] ON [dbo].[t_line_support]
GO
/****** Object:  Index [emp_no]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [emp_no] ON [dbo].[t_leave_form_history]
GO
/****** Object:  Index [emp_no]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [emp_no] ON [dbo].[t_leave_form]
GO
/****** Object:  Index [shift_group]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [shift_group] ON [dbo].[t_absences]
GO
/****** Object:  Index [emp_no]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [emp_no] ON [dbo].[t_absences]
GO
/****** Object:  Index [day]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [day] ON [dbo].[t_absences]
GO
/****** Object:  Index [ip]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP INDEX [ip] ON [dbo].[m_access_locations]
GO
/****** Object:  Table [dbo].[t_time_in_out]    Script Date: 2024/07/04 2:31:13 pm ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[t_time_in_out]') AND type in (N'U'))
DROP TABLE [dbo].[t_time_in_out]
GO
/****** Object:  Table [dbo].[t_shuttle_allocation]    Script Date: 2024/07/04 2:31:13 pm ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[t_shuttle_allocation]') AND type in (N'U'))
DROP TABLE [dbo].[t_shuttle_allocation]
GO
/****** Object:  Table [dbo].[t_notif_line_support]    Script Date: 2024/07/04 2:31:13 pm ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[t_notif_line_support]') AND type in (N'U'))
DROP TABLE [dbo].[t_notif_line_support]
GO
/****** Object:  Table [dbo].[t_line_support_history]    Script Date: 2024/07/04 2:31:13 pm ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[t_line_support_history]') AND type in (N'U'))
DROP TABLE [dbo].[t_line_support_history]
GO
/****** Object:  Table [dbo].[t_line_support]    Script Date: 2024/07/04 2:31:13 pm ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[t_line_support]') AND type in (N'U'))
DROP TABLE [dbo].[t_line_support]
GO
/****** Object:  Table [dbo].[t_leave_form_history]    Script Date: 2024/07/04 2:31:13 pm ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[t_leave_form_history]') AND type in (N'U'))
DROP TABLE [dbo].[t_leave_form_history]
GO
/****** Object:  Table [dbo].[t_leave_form]    Script Date: 2024/07/04 2:31:13 pm ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[t_leave_form]') AND type in (N'U'))
DROP TABLE [dbo].[t_leave_form]
GO
/****** Object:  Table [dbo].[t_absences]    Script Date: 2024/07/04 2:31:13 pm ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[t_absences]') AND type in (N'U'))
DROP TABLE [dbo].[t_absences]
GO
/****** Object:  Table [dbo].[m_sub_sections]    Script Date: 2024/07/04 2:31:13 pm ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[m_sub_sections]') AND type in (N'U'))
DROP TABLE [dbo].[m_sub_sections]
GO
/****** Object:  Table [dbo].[m_shuttle_routes]    Script Date: 2024/07/04 2:31:13 pm ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[m_shuttle_routes]') AND type in (N'U'))
DROP TABLE [dbo].[m_shuttle_routes]
GO
/****** Object:  Table [dbo].[m_providers]    Script Date: 2024/07/04 2:31:13 pm ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[m_providers]') AND type in (N'U'))
DROP TABLE [dbo].[m_providers]
GO
/****** Object:  Table [dbo].[m_process]    Script Date: 2024/07/04 2:31:13 pm ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[m_process]') AND type in (N'U'))
DROP TABLE [dbo].[m_process]
GO
/****** Object:  Table [dbo].[m_positions]    Script Date: 2024/07/04 2:31:13 pm ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[m_positions]') AND type in (N'U'))
DROP TABLE [dbo].[m_positions]
GO
/****** Object:  Table [dbo].[m_hr_accounts]    Script Date: 2024/07/04 2:31:13 pm ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[m_hr_accounts]') AND type in (N'U'))
DROP TABLE [dbo].[m_hr_accounts]
GO
/****** Object:  Table [dbo].[m_falp_groups]    Script Date: 2024/07/04 2:31:13 pm ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[m_falp_groups]') AND type in (N'U'))
DROP TABLE [dbo].[m_falp_groups]
GO
/****** Object:  Table [dbo].[m_employees]    Script Date: 2024/07/04 2:31:13 pm ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[m_employees]') AND type in (N'U'))
DROP TABLE [dbo].[m_employees]
GO
/****** Object:  Table [dbo].[m_dept]    Script Date: 2024/07/04 2:31:13 pm ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[m_dept]') AND type in (N'U'))
DROP TABLE [dbo].[m_dept]
GO
/****** Object:  Table [dbo].[m_control_area_accounts]    Script Date: 2024/07/04 2:31:13 pm ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[m_control_area_accounts]') AND type in (N'U'))
DROP TABLE [dbo].[m_control_area_accounts]
GO
/****** Object:  Table [dbo].[m_clinic_accounts]    Script Date: 2024/07/04 2:31:13 pm ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[m_clinic_accounts]') AND type in (N'U'))
DROP TABLE [dbo].[m_clinic_accounts]
GO
/****** Object:  Table [dbo].[m_accounts]    Script Date: 2024/07/04 2:31:13 pm ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[m_accounts]') AND type in (N'U'))
DROP TABLE [dbo].[m_accounts]
GO
/****** Object:  Table [dbo].[m_access_locations]    Script Date: 2024/07/04 2:31:13 pm ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[m_access_locations]') AND type in (N'U'))
DROP TABLE [dbo].[m_access_locations]
GO
/****** Object:  Schema [emp_mgt_db]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP SCHEMA [emp_mgt_db]
GO
USE [master]
GO
/****** Object:  Database [emp_mgt_db]    Script Date: 2024/07/04 2:31:13 pm ******/
DROP DATABASE [emp_mgt_db]
GO
/****** Object:  Database [emp_mgt_db]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE DATABASE [emp_mgt_db]
 CONTAINMENT = NONE
 ON  PRIMARY 
( NAME = N'emp_mgt_db', FILENAME = N'C:\Program Files\Microsoft SQL Server\MSSQL15.MSSQLSERVER\MSSQL\DATA\emp_mgt_db.mdf' , SIZE = 204800KB , MAXSIZE = UNLIMITED, FILEGROWTH = 65536KB )
 LOG ON 
( NAME = N'emp_mgt_db_log', FILENAME = N'C:\Program Files\Microsoft SQL Server\MSSQL15.MSSQLSERVER\MSSQL\DATA\emp_mgt_db_log.ldf' , SIZE = 401408KB , MAXSIZE = 2048GB , FILEGROWTH = 65536KB )
GO
IF (1 = FULLTEXTSERVICEPROPERTY('IsFullTextInstalled'))
begin
EXEC [emp_mgt_db].[dbo].[sp_fulltext_database] @action = 'enable'
end
GO
ALTER DATABASE [emp_mgt_db] SET ANSI_NULL_DEFAULT OFF 
GO
ALTER DATABASE [emp_mgt_db] SET ANSI_NULLS OFF 
GO
ALTER DATABASE [emp_mgt_db] SET ANSI_PADDING OFF 
GO
ALTER DATABASE [emp_mgt_db] SET ANSI_WARNINGS OFF 
GO
ALTER DATABASE [emp_mgt_db] SET ARITHABORT OFF 
GO
ALTER DATABASE [emp_mgt_db] SET AUTO_CLOSE OFF 
GO
ALTER DATABASE [emp_mgt_db] SET AUTO_SHRINK OFF 
GO
ALTER DATABASE [emp_mgt_db] SET AUTO_UPDATE_STATISTICS ON 
GO
ALTER DATABASE [emp_mgt_db] SET CURSOR_CLOSE_ON_COMMIT OFF 
GO
ALTER DATABASE [emp_mgt_db] SET CURSOR_DEFAULT  GLOBAL 
GO
ALTER DATABASE [emp_mgt_db] SET CONCAT_NULL_YIELDS_NULL OFF 
GO
ALTER DATABASE [emp_mgt_db] SET NUMERIC_ROUNDABORT OFF 
GO
ALTER DATABASE [emp_mgt_db] SET QUOTED_IDENTIFIER OFF 
GO
ALTER DATABASE [emp_mgt_db] SET RECURSIVE_TRIGGERS OFF 
GO
ALTER DATABASE [emp_mgt_db] SET  DISABLE_BROKER 
GO
ALTER DATABASE [emp_mgt_db] SET AUTO_UPDATE_STATISTICS_ASYNC OFF 
GO
ALTER DATABASE [emp_mgt_db] SET DATE_CORRELATION_OPTIMIZATION OFF 
GO
ALTER DATABASE [emp_mgt_db] SET TRUSTWORTHY OFF 
GO
ALTER DATABASE [emp_mgt_db] SET ALLOW_SNAPSHOT_ISOLATION OFF 
GO
ALTER DATABASE [emp_mgt_db] SET PARAMETERIZATION SIMPLE 
GO
ALTER DATABASE [emp_mgt_db] SET READ_COMMITTED_SNAPSHOT OFF 
GO
ALTER DATABASE [emp_mgt_db] SET HONOR_BROKER_PRIORITY OFF 
GO
ALTER DATABASE [emp_mgt_db] SET RECOVERY FULL 
GO
ALTER DATABASE [emp_mgt_db] SET  MULTI_USER 
GO
ALTER DATABASE [emp_mgt_db] SET PAGE_VERIFY CHECKSUM  
GO
ALTER DATABASE [emp_mgt_db] SET DB_CHAINING OFF 
GO
ALTER DATABASE [emp_mgt_db] SET FILESTREAM( NON_TRANSACTED_ACCESS = OFF ) 
GO
ALTER DATABASE [emp_mgt_db] SET TARGET_RECOVERY_TIME = 60 SECONDS 
GO
ALTER DATABASE [emp_mgt_db] SET DELAYED_DURABILITY = DISABLED 
GO
USE [emp_mgt_db]
GO
/****** Object:  Schema [emp_mgt_db]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE SCHEMA [emp_mgt_db]
GO
/****** Object:  Table [dbo].[m_access_locations]    Script Date: 2024/07/04 2:31:13 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[m_access_locations](
	[id] [int] IDENTITY(9,1) NOT NULL,
	[dept] [nvarchar](255) NULL,
	[section] [nvarchar](255) NULL,
	[line_no] [nvarchar](255) NULL,
	[ip] [nvarchar](100) NULL,
	[date_updated] [datetime2](2) NOT NULL,
 CONSTRAINT [PK_m_access_locations_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[m_accounts]    Script Date: 2024/07/04 2:31:13 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[m_accounts](
	[id] [int] IDENTITY(13,1) NOT NULL,
	[emp_no] [nvarchar](255) NOT NULL,
	[full_name] [nvarchar](255) NOT NULL,
	[dept] [nvarchar](255) NOT NULL,
	[section] [nvarchar](255) NULL,
	[line_no] [nvarchar](255) NULL,
	[shift_group] [nvarchar](100) NULL,
	[role] [nvarchar](255) NOT NULL,
	[date_updated] [datetime2](2) NOT NULL,
 CONSTRAINT [PK_m_accounts_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [m_accounts$emp_no] UNIQUE NONCLUSTERED 
(
	[emp_no] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[m_clinic_accounts]    Script Date: 2024/07/04 2:31:13 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[m_clinic_accounts](
	[id] [int] IDENTITY(2,1) NOT NULL,
	[emp_no] [nvarchar](255) NOT NULL,
	[full_name] [nvarchar](255) NOT NULL,
	[dept] [nvarchar](255) NOT NULL,
	[section] [nvarchar](255) NOT NULL,
	[line_no] [nvarchar](255) NOT NULL,
	[role] [nvarchar](255) NOT NULL,
	[date_updated] [datetime2](2) NOT NULL,
 CONSTRAINT [PK_m_clinic_accounts_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [m_clinic_accounts$emp_no] UNIQUE NONCLUSTERED 
(
	[emp_no] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[m_control_area_accounts]    Script Date: 2024/07/04 2:31:13 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[m_control_area_accounts](
	[id] [int] IDENTITY(13,1) NOT NULL,
	[emp_no] [nvarchar](255) NOT NULL,
	[full_name] [nvarchar](255) NOT NULL,
	[dept] [nvarchar](255) NOT NULL,
	[section] [nvarchar](255) NULL,
	[line_no] [nvarchar](255) NULL,
	[shift_group] [nvarchar](100) NULL,
	[role] [nvarchar](255) NOT NULL,
	[date_updated] [datetime2](2) NOT NULL,
 CONSTRAINT [PK_m_control_area_accounts_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [m_control_area_accounts$emp_no] UNIQUE NONCLUSTERED 
(
	[emp_no] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[m_dept]    Script Date: 2024/07/04 2:31:13 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[m_dept](
	[id] [int] IDENTITY(24,1) NOT NULL,
	[dept] [nvarchar](255) NOT NULL,
	[date_updated] [datetime2](2) NOT NULL,
 CONSTRAINT [PK_m_dept_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [m_dept$dept] UNIQUE NONCLUSTERED 
(
	[dept] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[m_employees]    Script Date: 2024/07/04 2:31:13 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[m_employees](
	[id] [int] IDENTITY(51,1) NOT NULL,
	[emp_no] [nvarchar](255) NOT NULL,
	[full_name] [nvarchar](255) NULL,
	[dept] [nvarchar](255) NULL,
	[section] [nvarchar](255) NULL,
	[sub_section] [nvarchar](255) NULL,
	[process] [nvarchar](255) NULL,
	[line_no] [nvarchar](255) NULL,
	[position] [nvarchar](255) NULL,
	[provider] [nvarchar](255) NULL,
	[gender] [nvarchar](20) NULL,
	[shift_group] [nvarchar](100) NULL,
	[date_hired] [date] NULL,
	[address] [nvarchar](625) NULL,
	[contact_no] [nvarchar](11) NULL,
	[emp_status] [nvarchar](255) NULL,
	[shuttle_route] [nvarchar](50) NULL,
	[emp_js_s] [nvarchar](255) NULL,
	[emp_js_s_no] [nvarchar](255) NULL,
	[emp_sv] [nvarchar](255) NULL,
	[emp_sv_no] [nvarchar](255) NULL,
	[emp_approver] [nvarchar](255) NULL,
	[emp_approver_no] [nvarchar](255) NULL,
	[resigned] [tinyint] NULL,
	[resigned_date] [date] NULL,
	[date_updated] [datetime2](2) NOT NULL,
 CONSTRAINT [PK_m_employees_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [m_employees$emp_no] UNIQUE NONCLUSTERED 
(
	[emp_no] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[m_falp_groups]    Script Date: 2024/07/04 2:31:13 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[m_falp_groups](
	[id] [int] IDENTITY(10,1) NOT NULL,
	[falp_group] [nvarchar](255) NOT NULL,
	[date_updated] [datetime2](2) NOT NULL,
 CONSTRAINT [PK_m_falp_groups_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [m_falp_groups$falp_group] UNIQUE NONCLUSTERED 
(
	[falp_group] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[m_hr_accounts]    Script Date: 2024/07/04 2:31:13 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[m_hr_accounts](
	[id] [int] IDENTITY(3,1) NOT NULL,
	[emp_no] [nvarchar](255) NOT NULL,
	[full_name] [nvarchar](255) NOT NULL,
	[dept] [nvarchar](255) NOT NULL,
	[section] [nvarchar](255) NOT NULL,
	[line_no] [nvarchar](255) NOT NULL,
	[role] [nvarchar](255) NOT NULL,
	[date_updated] [datetime2](2) NOT NULL,
 CONSTRAINT [PK_m_hr_accounts_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [m_hr_accounts$emp_no] UNIQUE NONCLUSTERED 
(
	[emp_no] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[m_positions]    Script Date: 2024/07/04 2:31:13 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[m_positions](
	[id] [int] IDENTITY(8,1) NOT NULL,
	[position] [nvarchar](255) NOT NULL,
	[date_updated] [datetime2](2) NOT NULL,
 CONSTRAINT [PK_m_positions_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [m_positions$position] UNIQUE NONCLUSTERED 
(
	[position] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [m_positions$provider] UNIQUE NONCLUSTERED 
(
	[position] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[m_process]    Script Date: 2024/07/04 2:31:13 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[m_process](
	[id] [int] IDENTITY(6,1) NOT NULL,
	[process] [nvarchar](255) NOT NULL,
	[date_updated] [datetime2](2) NOT NULL,
 CONSTRAINT [PK_m_process_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [m_process$process] UNIQUE NONCLUSTERED 
(
	[process] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[m_providers]    Script Date: 2024/07/04 2:31:13 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[m_providers](
	[id] [int] IDENTITY(9,1) NOT NULL,
	[provider] [nvarchar](255) NOT NULL,
	[date_updated] [datetime2](2) NOT NULL,
 CONSTRAINT [PK_m_providers_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [m_providers$provider] UNIQUE NONCLUSTERED 
(
	[provider] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[m_shuttle_routes]    Script Date: 2024/07/04 2:31:13 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[m_shuttle_routes](
	[id] [int] IDENTITY(14,1) NOT NULL,
	[shuttle_route] [nvarchar](50) NOT NULL,
	[date_updated] [datetime2](2) NOT NULL,
 CONSTRAINT [PK_m_shuttle_routes_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [m_shuttle_routes$shuttle_route] UNIQUE NONCLUSTERED 
(
	[shuttle_route] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[m_sub_sections]    Script Date: 2024/07/04 2:31:13 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[m_sub_sections](
	[id] [int] IDENTITY(91,1) NOT NULL,
	[sub_section] [nvarchar](255) NOT NULL,
	[date_updated] [datetime2](2) NOT NULL,
 CONSTRAINT [PK_m_sub_sections_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [m_sub_sections$sub_section] UNIQUE NONCLUSTERED 
(
	[sub_section] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[t_absences]    Script Date: 2024/07/04 2:31:13 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[t_absences](
	[id] [int] IDENTITY(5,1) NOT NULL,
	[emp_no] [nvarchar](255) NOT NULL,
	[day] [date] NOT NULL,
	[shift_group] [nvarchar](100) NULL,
	[absent_type] [nvarchar](255) NOT NULL,
	[reason] [nvarchar](255) NOT NULL,
	[date_updated] [datetime2](2) NOT NULL,
 CONSTRAINT [PK_t_absences_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[t_leave_form]    Script Date: 2024/07/04 2:31:13 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[t_leave_form](
	[id] [int] IDENTITY(7,1) NOT NULL,
	[leave_form_id] [nvarchar](255) NOT NULL,
	[emp_no] [nvarchar](255) NOT NULL,
	[date_filed] [date] NOT NULL,
	[address] [nvarchar](625) NOT NULL,
	[contact_no] [nvarchar](11) NOT NULL,
	[leave_type] [nvarchar](255) NOT NULL,
	[leave_date_from] [date] NOT NULL,
	[leave_date_to] [date] NOT NULL,
	[total_leave_days] [int] NOT NULL,
	[irt_phone_call] [tinyint] NOT NULL,
	[irt_letter] [tinyint] NOT NULL,
	[irb] [nvarchar](255) NOT NULL,
	[reason] [nvarchar](255) NOT NULL,
	[issued_by] [nvarchar](255) NOT NULL,
	[js_s] [nvarchar](255) NOT NULL,
	[sv] [nvarchar](255) NOT NULL,
	[approver] [nvarchar](255) NOT NULL,
	[disapproved_by] [nvarchar](255) NOT NULL,
	[leave_form_status] [nvarchar](255) NOT NULL,
	[sl_r1_1_hrs] [int] NOT NULL,
	[sl_r1_1_date] [date] NULL,
	[sl_r1_1_time_in] [time](7) NULL,
	[sl_r1_1_time_out] [time](7) NULL,
	[sl_r1_2_days] [int] NOT NULL,
	[sl_r1_3_date] [date] NULL,
	[sl_rc_1_days] [int] NOT NULL,
	[sl_rc_2_from] [date] NULL,
	[sl_rc_2_to] [date] NULL,
	[sl_rc_3_oc] [tinyint] NOT NULL,
	[sl_rc_4_hm] [tinyint] NOT NULL,
	[sl_rc_mgh] [tinyint] NOT NULL,
	[sl_r2] [nvarchar](255) NOT NULL,
	[sl_dr_name] [nvarchar](255) NOT NULL,
	[sl_dr_date] [date] NULL,
	[date_updated] [datetime2](2) NOT NULL,
 CONSTRAINT [PK_t_leave_form_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [t_leave_form$leave_form_id] UNIQUE NONCLUSTERED 
(
	[leave_form_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[t_leave_form_history]    Script Date: 2024/07/04 2:31:13 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[t_leave_form_history](
	[id] [int] IDENTITY(3,1) NOT NULL,
	[leave_form_id] [nvarchar](255) NOT NULL,
	[emp_no] [nvarchar](255) NOT NULL,
	[date_filed] [date] NOT NULL,
	[address] [nvarchar](625) NOT NULL,
	[contact_no] [nvarchar](11) NOT NULL,
	[leave_type] [nvarchar](255) NOT NULL,
	[leave_date_from] [date] NOT NULL,
	[leave_date_to] [date] NOT NULL,
	[total_leave_days] [int] NOT NULL,
	[irt_phone_call] [tinyint] NOT NULL,
	[irt_letter] [tinyint] NOT NULL,
	[irb] [nvarchar](255) NOT NULL,
	[reason] [nvarchar](255) NOT NULL,
	[issued_by] [nvarchar](255) NOT NULL,
	[js_s] [nvarchar](255) NOT NULL,
	[sv] [nvarchar](255) NOT NULL,
	[approver] [nvarchar](255) NOT NULL,
	[disapproved_by] [nvarchar](255) NOT NULL,
	[leave_form_status] [nvarchar](255) NOT NULL,
	[sl_r1_1_hrs] [int] NOT NULL,
	[sl_r1_1_date] [date] NULL,
	[sl_r1_1_time_in] [time](7) NULL,
	[sl_r1_1_time_out] [time](7) NULL,
	[sl_r1_2_days] [int] NOT NULL,
	[sl_r1_3_date] [date] NULL,
	[sl_rc_1_days] [int] NOT NULL,
	[sl_rc_2_from] [date] NULL,
	[sl_rc_2_to] [date] NULL,
	[sl_rc_3_oc] [tinyint] NOT NULL,
	[sl_rc_4_hm] [tinyint] NOT NULL,
	[sl_rc_mgh] [tinyint] NOT NULL,
	[sl_r2] [nvarchar](255) NOT NULL,
	[sl_dr_name] [nvarchar](255) NOT NULL,
	[sl_dr_date] [date] NULL,
	[date_updated] [datetime2](2) NOT NULL,
 CONSTRAINT [PK_t_leave_form_history_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [t_leave_form_history$leave_form_id] UNIQUE NONCLUSTERED 
(
	[leave_form_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[t_line_support]    Script Date: 2024/07/04 2:31:13 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[t_line_support](
	[id] [int] IDENTITY(48,1) NOT NULL,
	[line_support_id] [nvarchar](255) NOT NULL,
	[emp_no] [nvarchar](255) NOT NULL,
	[day] [date] NOT NULL,
	[shift] [nvarchar](5) NOT NULL,
	[line_no_from] [nvarchar](255) NOT NULL,
	[line_no_to] [nvarchar](255) NOT NULL,
	[set_by] [nvarchar](255) NOT NULL,
	[set_by_no] [nvarchar](255) NOT NULL,
	[set_status_by] [nvarchar](255) NULL,
	[set_status_by_no] [nvarchar](255) NULL,
	[status] [nvarchar](255) NOT NULL,
	[date_updated] [datetime2](2) NOT NULL,
 CONSTRAINT [PK_t_line_support_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[t_line_support_history]    Script Date: 2024/07/04 2:31:13 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[t_line_support_history](
	[id] [int] IDENTITY(13,1) NOT NULL,
	[line_support_id] [nvarchar](255) NOT NULL,
	[emp_no] [nvarchar](255) NOT NULL,
	[day] [date] NOT NULL,
	[shift] [nvarchar](5) NOT NULL,
	[line_no_from] [nvarchar](255) NOT NULL,
	[line_no_to] [nvarchar](255) NOT NULL,
	[set_by] [nvarchar](255) NOT NULL,
	[set_by_no] [nvarchar](255) NOT NULL,
	[set_status_by] [nvarchar](255) NOT NULL,
	[set_status_by_no] [nvarchar](255) NOT NULL,
	[status] [nvarchar](255) NOT NULL,
	[date_updated] [datetime2](2) NOT NULL,
 CONSTRAINT [PK_t_line_support_history_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[t_notif_line_support]    Script Date: 2024/07/04 2:31:13 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[t_notif_line_support](
	[id] [int] IDENTITY(18,1) NOT NULL,
	[emp_no] [nvarchar](255) NOT NULL,
	[pending_ls] [int] NOT NULL,
	[accepted_ls] [int] NOT NULL,
	[rejected_ls] [int] NOT NULL,
 CONSTRAINT [PK_t_notif_line_support_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [t_notif_line_support$emp_no] UNIQUE NONCLUSTERED 
(
	[emp_no] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[t_shuttle_allocation]    Script Date: 2024/07/04 2:31:13 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[t_shuttle_allocation](
	[id] [int] IDENTITY(17,1) NOT NULL,
	[emp_no] [nvarchar](255) NOT NULL,
	[dept] [nvarchar](255) NOT NULL,
	[section] [nvarchar](255) NOT NULL,
	[line_no] [nvarchar](255) NOT NULL,
	[day] [date] NOT NULL,
	[shift] [nvarchar](5) NOT NULL,
	[shift_group] [nvarchar](100) NULL,
	[shuttle_route] [nvarchar](50) NOT NULL,
	[out_5] [int] NOT NULL,
	[out_6] [int] NOT NULL,
	[out_7] [int] NOT NULL,
	[out_8] [int] NOT NULL,
	[set_by] [nvarchar](255) NOT NULL,
	[date_updated] [datetime2](2) NOT NULL,
 CONSTRAINT [PK_t_shuttle_allocation_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[t_time_in_out]    Script Date: 2024/07/04 2:31:13 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[t_time_in_out](
	[id] [int] IDENTITY(130,1) NOT NULL,
	[emp_no] [nvarchar](255) NOT NULL,
	[day] [date] NOT NULL,
	[shift] [nvarchar](5) NOT NULL,
	[time_in] [datetime2](2) NOT NULL,
	[time_out] [datetime2](2) NULL,
	[ip] [nvarchar](100) NULL,
	[date_updated] [datetime2](2) NOT NULL,
 CONSTRAINT [PK_t_time_in_out_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [ip]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [ip] ON [dbo].[m_access_locations]
(
	[ip] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
/****** Object:  Index [day]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [day] ON [dbo].[t_absences]
(
	[day] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [emp_no]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [emp_no] ON [dbo].[t_absences]
(
	[emp_no] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [shift_group]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [shift_group] ON [dbo].[t_absences]
(
	[shift_group] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [emp_no]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [emp_no] ON [dbo].[t_leave_form]
(
	[emp_no] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [emp_no]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [emp_no] ON [dbo].[t_leave_form_history]
(
	[emp_no] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
/****** Object:  Index [day]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [day] ON [dbo].[t_line_support]
(
	[day] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [emp_no]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [emp_no] ON [dbo].[t_line_support]
(
	[emp_no] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [line_support_id]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [line_support_id] ON [dbo].[t_line_support]
(
	[line_support_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [shift]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [shift] ON [dbo].[t_line_support]
(
	[shift] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
/****** Object:  Index [day]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [day] ON [dbo].[t_line_support_history]
(
	[day] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [emp_no]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [emp_no] ON [dbo].[t_line_support_history]
(
	[emp_no] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [line_support_id]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [line_support_id] ON [dbo].[t_line_support_history]
(
	[line_support_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [shift]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [shift] ON [dbo].[t_line_support_history]
(
	[shift] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
/****** Object:  Index [day]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [day] ON [dbo].[t_shuttle_allocation]
(
	[day] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [emp_no]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [emp_no] ON [dbo].[t_shuttle_allocation]
(
	[emp_no] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [shift]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [shift] ON [dbo].[t_shuttle_allocation]
(
	[shift] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [shift_group]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [shift_group] ON [dbo].[t_shuttle_allocation]
(
	[shift_group] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
/****** Object:  Index [day]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [day] ON [dbo].[t_time_in_out]
(
	[day] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [emp_no]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [emp_no] ON [dbo].[t_time_in_out]
(
	[emp_no] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [ip]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [ip] ON [dbo].[t_time_in_out]
(
	[ip] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
SET ANSI_PADDING ON
GO
/****** Object:  Index [shift]    Script Date: 2024/07/04 2:31:13 pm ******/
CREATE NONCLUSTERED INDEX [shift] ON [dbo].[t_time_in_out]
(
	[shift] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
ALTER TABLE [dbo].[m_access_locations] ADD  DEFAULT (NULL) FOR [dept]
GO
ALTER TABLE [dbo].[m_access_locations] ADD  DEFAULT (NULL) FOR [section]
GO
ALTER TABLE [dbo].[m_access_locations] ADD  DEFAULT (NULL) FOR [line_no]
GO
ALTER TABLE [dbo].[m_access_locations] ADD  DEFAULT (NULL) FOR [ip]
GO
ALTER TABLE [dbo].[m_access_locations] ADD  DEFAULT (getdate()) FOR [date_updated]
GO
ALTER TABLE [dbo].[m_accounts] ADD  DEFAULT (NULL) FOR [section]
GO
ALTER TABLE [dbo].[m_accounts] ADD  DEFAULT (NULL) FOR [line_no]
GO
ALTER TABLE [dbo].[m_accounts] ADD  DEFAULT (NULL) FOR [shift_group]
GO
ALTER TABLE [dbo].[m_accounts] ADD  DEFAULT (getdate()) FOR [date_updated]
GO
ALTER TABLE [dbo].[m_clinic_accounts] ADD  DEFAULT (getdate()) FOR [date_updated]
GO
ALTER TABLE [dbo].[m_control_area_accounts] ADD  DEFAULT (NULL) FOR [section]
GO
ALTER TABLE [dbo].[m_control_area_accounts] ADD  DEFAULT (NULL) FOR [line_no]
GO
ALTER TABLE [dbo].[m_control_area_accounts] ADD  DEFAULT (NULL) FOR [shift_group]
GO
ALTER TABLE [dbo].[m_control_area_accounts] ADD  DEFAULT (getdate()) FOR [date_updated]
GO
ALTER TABLE [dbo].[m_dept] ADD  DEFAULT (getdate()) FOR [date_updated]
GO
ALTER TABLE [dbo].[m_employees] ADD  DEFAULT (NULL) FOR [full_name]
GO
ALTER TABLE [dbo].[m_employees] ADD  CONSTRAINT [DF__m_employee__dept__1975C517]  DEFAULT (N'Undefined') FOR [dept]
GO
ALTER TABLE [dbo].[m_employees] ADD  CONSTRAINT [DF__m_employe__secti__1A69E950]  DEFAULT (N'Undefined') FOR [section]
GO
ALTER TABLE [dbo].[m_employees] ADD  CONSTRAINT [DF__m_employe__sub_s__1B5E0D89]  DEFAULT (N'Undefined') FOR [sub_section]
GO
ALTER TABLE [dbo].[m_employees] ADD  CONSTRAINT [DF__m_employe__proce__1C5231C2]  DEFAULT (N'Undefined') FOR [process]
GO
ALTER TABLE [dbo].[m_employees] ADD  CONSTRAINT [DF__m_employe__line___1D4655FB]  DEFAULT (N'Undefined') FOR [line_no]
GO
ALTER TABLE [dbo].[m_employees] ADD  DEFAULT (NULL) FOR [position]
GO
ALTER TABLE [dbo].[m_employees] ADD  DEFAULT (NULL) FOR [provider]
GO
ALTER TABLE [dbo].[m_employees] ADD  DEFAULT (NULL) FOR [gender]
GO
ALTER TABLE [dbo].[m_employees] ADD  CONSTRAINT [DF__m_employe__shift__2116E6DF]  DEFAULT (N'ADS') FOR [shift_group]
GO
ALTER TABLE [dbo].[m_employees] ADD  DEFAULT (NULL) FOR [date_hired]
GO
ALTER TABLE [dbo].[m_employees] ADD  DEFAULT (NULL) FOR [address]
GO
ALTER TABLE [dbo].[m_employees] ADD  DEFAULT (NULL) FOR [contact_no]
GO
ALTER TABLE [dbo].[m_employees] ADD  DEFAULT (NULL) FOR [emp_status]
GO
ALTER TABLE [dbo].[m_employees] ADD  DEFAULT (NULL) FOR [shuttle_route]
GO
ALTER TABLE [dbo].[m_employees] ADD  DEFAULT (NULL) FOR [emp_js_s]
GO
ALTER TABLE [dbo].[m_employees] ADD  DEFAULT (NULL) FOR [emp_js_s_no]
GO
ALTER TABLE [dbo].[m_employees] ADD  DEFAULT (NULL) FOR [emp_sv]
GO
ALTER TABLE [dbo].[m_employees] ADD  DEFAULT (NULL) FOR [emp_sv_no]
GO
ALTER TABLE [dbo].[m_employees] ADD  DEFAULT (NULL) FOR [emp_approver]
GO
ALTER TABLE [dbo].[m_employees] ADD  DEFAULT (NULL) FOR [emp_approver_no]
GO
ALTER TABLE [dbo].[m_employees] ADD  DEFAULT ((0)) FOR [resigned]
GO
ALTER TABLE [dbo].[m_employees] ADD  DEFAULT (NULL) FOR [resigned_date]
GO
ALTER TABLE [dbo].[m_employees] ADD  DEFAULT (getdate()) FOR [date_updated]
GO
ALTER TABLE [dbo].[m_falp_groups] ADD  DEFAULT (getdate()) FOR [date_updated]
GO
ALTER TABLE [dbo].[m_hr_accounts] ADD  DEFAULT (getdate()) FOR [date_updated]
GO
ALTER TABLE [dbo].[m_positions] ADD  DEFAULT (getdate()) FOR [date_updated]
GO
ALTER TABLE [dbo].[m_process] ADD  DEFAULT (getdate()) FOR [date_updated]
GO
ALTER TABLE [dbo].[m_providers] ADD  DEFAULT (getdate()) FOR [date_updated]
GO
ALTER TABLE [dbo].[m_shuttle_routes] ADD  DEFAULT (getdate()) FOR [date_updated]
GO
ALTER TABLE [dbo].[m_sub_sections] ADD  DEFAULT (getdate()) FOR [date_updated]
GO
ALTER TABLE [dbo].[t_absences] ADD  DEFAULT (getdate()) FOR [day]
GO
ALTER TABLE [dbo].[t_absences] ADD  DEFAULT (NULL) FOR [shift_group]
GO
ALTER TABLE [dbo].[t_absences] ADD  DEFAULT (getdate()) FOR [date_updated]
GO
ALTER TABLE [dbo].[t_leave_form] ADD  DEFAULT (getdate()) FOR [date_filed]
GO
ALTER TABLE [dbo].[t_leave_form] ADD  DEFAULT ((1)) FOR [total_leave_days]
GO
ALTER TABLE [dbo].[t_leave_form] ADD  DEFAULT (N'pending') FOR [leave_form_status]
GO
ALTER TABLE [dbo].[t_leave_form] ADD  DEFAULT (NULL) FOR [sl_r1_1_date]
GO
ALTER TABLE [dbo].[t_leave_form] ADD  DEFAULT (NULL) FOR [sl_r1_1_time_in]
GO
ALTER TABLE [dbo].[t_leave_form] ADD  DEFAULT (NULL) FOR [sl_r1_1_time_out]
GO
ALTER TABLE [dbo].[t_leave_form] ADD  DEFAULT (NULL) FOR [sl_r1_3_date]
GO
ALTER TABLE [dbo].[t_leave_form] ADD  DEFAULT (NULL) FOR [sl_rc_2_from]
GO
ALTER TABLE [dbo].[t_leave_form] ADD  DEFAULT (NULL) FOR [sl_rc_2_to]
GO
ALTER TABLE [dbo].[t_leave_form] ADD  DEFAULT (NULL) FOR [sl_dr_date]
GO
ALTER TABLE [dbo].[t_leave_form] ADD  DEFAULT (getdate()) FOR [date_updated]
GO
ALTER TABLE [dbo].[t_leave_form_history] ADD  DEFAULT (getdate()) FOR [date_filed]
GO
ALTER TABLE [dbo].[t_leave_form_history] ADD  DEFAULT ((1)) FOR [total_leave_days]
GO
ALTER TABLE [dbo].[t_leave_form_history] ADD  DEFAULT (N'pending') FOR [leave_form_status]
GO
ALTER TABLE [dbo].[t_leave_form_history] ADD  DEFAULT (NULL) FOR [sl_r1_1_date]
GO
ALTER TABLE [dbo].[t_leave_form_history] ADD  DEFAULT (NULL) FOR [sl_r1_1_time_in]
GO
ALTER TABLE [dbo].[t_leave_form_history] ADD  DEFAULT (NULL) FOR [sl_r1_1_time_out]
GO
ALTER TABLE [dbo].[t_leave_form_history] ADD  DEFAULT (NULL) FOR [sl_r1_3_date]
GO
ALTER TABLE [dbo].[t_leave_form_history] ADD  DEFAULT (NULL) FOR [sl_rc_2_from]
GO
ALTER TABLE [dbo].[t_leave_form_history] ADD  DEFAULT (NULL) FOR [sl_rc_2_to]
GO
ALTER TABLE [dbo].[t_leave_form_history] ADD  DEFAULT (NULL) FOR [sl_dr_date]
GO
ALTER TABLE [dbo].[t_leave_form_history] ADD  DEFAULT (getdate()) FOR [date_updated]
GO
ALTER TABLE [dbo].[t_line_support] ADD  DEFAULT (getdate()) FOR [day]
GO
ALTER TABLE [dbo].[t_line_support] ADD  DEFAULT (N'added') FOR [status]
GO
ALTER TABLE [dbo].[t_line_support] ADD  DEFAULT (getdate()) FOR [date_updated]
GO
ALTER TABLE [dbo].[t_line_support_history] ADD  DEFAULT (getdate()) FOR [day]
GO
ALTER TABLE [dbo].[t_line_support_history] ADD  DEFAULT (N'added') FOR [status]
GO
ALTER TABLE [dbo].[t_line_support_history] ADD  DEFAULT (getdate()) FOR [date_updated]
GO
ALTER TABLE [dbo].[t_notif_line_support] ADD  DEFAULT ((0)) FOR [pending_ls]
GO
ALTER TABLE [dbo].[t_notif_line_support] ADD  DEFAULT ((0)) FOR [accepted_ls]
GO
ALTER TABLE [dbo].[t_notif_line_support] ADD  DEFAULT ((0)) FOR [rejected_ls]
GO
ALTER TABLE [dbo].[t_shuttle_allocation] ADD  DEFAULT (NULL) FOR [shift_group]
GO
ALTER TABLE [dbo].[t_shuttle_allocation] ADD  DEFAULT ((0)) FOR [out_5]
GO
ALTER TABLE [dbo].[t_shuttle_allocation] ADD  DEFAULT ((0)) FOR [out_6]
GO
ALTER TABLE [dbo].[t_shuttle_allocation] ADD  DEFAULT ((0)) FOR [out_7]
GO
ALTER TABLE [dbo].[t_shuttle_allocation] ADD  DEFAULT ((0)) FOR [out_8]
GO
ALTER TABLE [dbo].[t_shuttle_allocation] ADD  DEFAULT (getdate()) FOR [date_updated]
GO
ALTER TABLE [dbo].[t_time_in_out] ADD  DEFAULT (getdate()) FOR [day]
GO
ALTER TABLE [dbo].[t_time_in_out] ADD  DEFAULT (getdate()) FOR [time_in]
GO
ALTER TABLE [dbo].[t_time_in_out] ADD  DEFAULT (NULL) FOR [time_out]
GO
ALTER TABLE [dbo].[t_time_in_out] ADD  DEFAULT (NULL) FOR [ip]
GO
ALTER TABLE [dbo].[t_time_in_out] ADD  DEFAULT (getdate()) FOR [date_updated]
GO
USE [master]
GO
ALTER DATABASE [emp_mgt_db] SET  READ_WRITE 
GO
