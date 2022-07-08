How it works:

1. you create datasheet:
    $booksDatasheet = new Datasheet($queryBuilder);
   
2. it renders in twig:
    {{ datasheet(booksDatasheet) }}
   
What happens inside:

1. DatasheetTwigExtension::getDatasheet
2. DatasheetBuilder::build
3. DatasheetBuilder::expose 
4. DatasheetBuilder::getDataReader
5. DatasheetBuilder::addFiltersToDataReader
6. DatasheetBuilder::getData
7. DatasheetBuilder::getColumns
8. DatasheetBuilder::showAddUpdateHideColumns
8. DatasheetBuilder::typifyColumns
9. DatasheetViewBuilder::getDatasheetView
