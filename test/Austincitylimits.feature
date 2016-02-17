Feature: Austin City Limits

  CiviCRM extension to assign Austin City Council District (using GEOS GEOPHP and a shapefile from the Austin Govt) to Custom Field on Contact if applicable.

  Rules:
  Will attempt to figure out and add an Austin City Council District:
  -When an address is created or edited
  -If it is in Texas
  -Has lat and long (is geocoded)
  -If an address is location type home

  Will delete the City Council district
  -When a home address is deleted


Scenario Outline: creating a new address
  Given the contacts address <locationtype>
  When the <address>, <city> <zip> is created
  And the state is <state>
  And the CCD entered is <CCDe>
  And the geocoder status is <GeoCoder>
  Then the City Council District Custom Field should be automatically saved as <CCD>

  Examples:
    | locationtype | address          | zip   | city   | state | CCDe | geocoder |CCD   |
    |    Home      | 619 Congress Ave | 78701 | Austin | Texas | NULL | On       | 9    |
    |    Work      | 619 Congress Ave | 78701 | Austin | Texas | NULL | On       | NULL |
    |    Billing   | 619 Congress Ave | 78701 | Austin | Texas | NULL | On       | NULL |
    |    Home      | 501 Crawford St  | 77002 | Houston| Texas | NULL | On       | NULL |
    |    Home      | 501 E High St    | 45056 | Oxford | Ohio  | NULL | On       | NULL |
    |    Home      | 619 Congress Ave | 78701 | Austin | Texas | 8    | On       | 8    |
    |    Home      | 619 Congress Ave | 78701 | Austin | Texas | NULL | Off      | NULL |
    |    Work      | 619 Congress Ave | 78701 | Austin | Texas | NULL | Off      | NULL |

Scenario Outline: editing a home address in Texas with lat lng with a CCD already assigned
  Given the contact has an exsisting home address ex 2201 Barton Springs Rd, Austin, TX 78746 City Council District: 8
  When the address edited to <locationtype>
  And <address>, <city>, <state> <zip>
  And the CCD entered is <changed> to <CCDe>
  Then the City Council District Custom Field should be automatically saved as <CCD>

  Examples:
    | locationtype | address          | zip   | city   | state | changed |CCDe  | CCD  |
    |    Home      | 619 Congress Ave | 78701 | Austin | Texas | Not     | N/A  | 9    |
    |    Work      | 619 Congress Ave | 78701 | Austin | Texas | Not     | N/A  | 8    |
    |    Home      | 501 E High St    | 45056 | Oxford | Ohio  | Not     | N/A  | NULL |
    |    Home      | 501 Crawford St  | 77002 | Houston| Texas | Not     | N/A  | NULL |
    |    Home      | 619 Congress Ave | 78701 | Austin | Texas | yes     | 3    | 9    |

Scenario Outline: editing a home address in not in texas
  Given the contact has an exsisting home address of 501 E High St,Oxford, Ohio 45056
  When the address edited to <locationtype>
  And <address>, <city>, <state> <zip>
  And the CCD entered is <changed> to <CCDe>
  Then the City Council District Custom Field should be automatically saved as <CCD>

  Examples:
    | locationtype | address          | zip   | city   | state | changed |CCDe  | CCD  |
    |    Home      | 619 Congress Ave | 78701 | Austin | Texas | Not     | N/A  | 9    |
    |    Work      | 619 Congress Ave | 78701 | Austin | Texas | Not     | N/A  | NULL |
    |    Home      | 501 E High St    | 45056 | Oxford | Ohio  | Not     | N/A  | NULL |
    |    Home      | 501 Crawford St  | 77002 | Houston| Texas | Not     | N/A  | NULL |
    |    Home      | 619 Congress Ave | 78701 | Austin | Texas | yes     | 3    | 9    |

Scenario Outline: deleting addresses
  Given the contacts address <locationtype>
  When the <address>, <city> <zip> <state> is deleted
  Then the City Council District Custom Field should be automatically saved as <CCD>

  Examples:
    | locationtype | address          | zip   | city   | state | CCD  |
    |    Home      | 619 Congress Ave | 78701 | Austin | Texas | NULL |
    |    Work      | 619 Congress Ave | 78701 | Austin | Texas | NULL |
    |    Billing   | 619 Congress Ave | 78701 | Austin | Texas | NULL |
    |    Home      | 501 Crawford St  | 77002 | Houston| Texas | NULL |
    |    Home      | 501 E High St    | 45056 | Oxford | Ohio  | NULL |

Scenario Outline: inheriting an address
  Given the contact has an exsisting inerited home address of 2201 Barton Springs Rd, Austin, TX 78746 City Council District: 8
  When the address edited to <locationtype>
  And <address>, <city>, <state> <zip>
  Then the City Council District Custom Field should be automatically saved as <CCD>

  Examples:
    | locationtype | address          | zip   | city   | state | CCD  |
    |    Home      | 619 Congress Ave | 78701 | Austin | Texa  | 9    |
    |    Work      | 619 Congress Ave | 78701 | Austin | Texas | 8    |
    |    Billing   | 619 Congress Ave | 78701 | Austin | Texas | 8    |
    |    Home      | 501 Crawford St  | 77002 | Houston| Texas | 8    |
    |    Home      | 501 E High St    | 45056 | Oxford | Ohio  | NULL |

Scenario Outline: multiple addresses
  Given the contact has an exsisting address <add1type> <address1>
  And an <add2type> address of <address2>
  Then the City Council District Custom Field should be automatically saved as <CCD>

  Examples:
    | add1type | address1         | add2type | address2                                 | CCD  |
    | Home     | 619 Congress Ave | Billing  | 2201 Barton Springs Rd, Austin, TX 78746 | 9    |
    | Work     | 619 Congress Ave | Home     | 2201 Barton Springs Rd, Austin, TX 78746 | 8    |
    | Work     | 619 Congress Ave | Home     | 501 Crawford St, Houston, Texas 77002    | NULL |   
