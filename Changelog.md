## Introduction ##

When registering any changelog to the CWR checklist and specimens database, please bear in mind the following suggestions:

  * Each registered change should have the date in which it was done (please follow this format: YYYY-MM-DD)

  * Each changelog should have a brief explanation of the change/improvement made

  * Please use bullets for each changelog

## Changelogs ##

  * 2012-04-16: Column "ID" in the table "Herbaria\_data" was deleted. A new primary key was assigned as the combination between the columns "Taxon\_ID" and "Code". (In this process some repeated records were also deleted).

  * 2012-04-16: table "Concepts", Change: Set ID to be auto incremental

  * 2012-04-16: table "Distribution", Change: Set ID to be auto incremental

  * 2012-04-16: table "Distribution\_detail", Change: Set ID to be auto incremental

  * 2012-04-16: table "Classification\_ref" Change: Dropped column 'Description' and renamed 'Path' to 'Webpage'

  * 2012-04-12: New table named "raw\_occurrences" was added to the database.

  * 2012-03-26: New column named "ISO\_Alpha2" was added to the table "countries".

  * 2012-03-20: New column named "Scientific\_Name" was added to the table "species". (This will help us to improve the performance in the search section).

  * 2012-02-17: TABLE LIT\_classification\_ref renamed to Classification\_ref

  * 2012-02-17: TABLE LIT\_classification\_ref - Field Type for columns ID and Taxon\_ID changed from DOUBLE to INTEGER.

  * 2011-02-05: NEW TABLE:  breeding\_category\_lookup - this is the table that holds the breeding use categories taken from GRIN

  * 2011-02-05: NEW TABLE:  Breeding\_taxon\_and\_category - this links the taxon\_id to the breeding use category defined by GRIN.

  * 2011-02-05: TABLE breeding\_data: changed ids to ints rather than doubles

  * 2011-02-05: TABLE breeding\_ref: changed id to int, auto increment

  * 2011-02-05: TABLE Lit\_concepts\_ref: fields were changed so the whole reference is in one field. Fields now are: ID, Concept\_ID, Webpage, Ref.