%define packagename eq
%define phpgwdirname eq
%define version 0.0.1.000

# This is for Mandrake RPMS 
# (move these below the RedHat ones for Mandrake RPMs)
%define httpdroot  /var/www/html/phpgroupware
%define packaging 1mdk

# This is for RedHat RPMS
# (move these below the Mandrake ones for RedHat RPMs)
%define httpdroot  /home/httpd/html/phpgroupware
%define packaging 1

Summary: Tools for Managing a Priesthood Quorum app for phpGroupWare. 
Name: %{packagename}
Version: %{version}
Release: %{packaging}
Copyright: GPL
Group: Web/Database
URL: http://www.phpgroupware.org/apps/eq/
Source0: ftp://ftp.sourceforge.net/pub/sourceforge/phpgroupware/%{packagename}-%{version}.tar.bz2
BuildRoot: %{_tmppath}/%{packagename}-buildroot
Prefix: %{httpdroot}
Buildarch: noarch
requires: phpgroupware >= 0.9.10
AutoReq: 0

%description
This is an Priesthood Quorum Presidency Application.

%prep
%setup -n %{phpgwdirname}

%build
# no build required

%install
rm -rf $RPM_BUILD_ROOT
mkdir -p $RPM_BUILD_ROOT%{prefix}/%{phpgwdirname}
cp -aRf * $RPM_BUILD_ROOT%{prefix}/%{phpgwdirname}

%clean
rm -rf $RPM_BUILD_ROOT

%post

%postun

%files
%{prefix}/%{phpgwdirname}

%changelog
- EQ App Update

# end of file
