/***************************************************************************************************************************
                                                    VARIABLES
****************************************************************************************************************************/
var pagNav = document.getElementById('paginate');
var tagA = document.getElementsByName('tagA');
// Gobal variables
var curArray = fullArray;
var current_page = 1;
var records_per_page = 7;
var last_page = 1;
/***************************************************************************************************************************
                                                FUNCTION OF PAGINATION
****************************************************************************************************************************/
// Go to prev page function
function prevPage() {
    if (current_page > 1) {
        current_page--;
        init(current_page);
    }
}
// Go to next page function
function nextPage() {
    if (current_page < last_page) {
        current_page++;
        init(current_page);
    }
}
// Event listener for the number list of pagination (tag <a>)
// Change the current pag
function aListener() {
    for (var i = 0; i < tagA.length; i++) {
        tagA[i].addEventListener("click", function() {
            current_page = Number(this.id);
            init(current_page);
        });
    }
}
// Calculate max number of pages in pagination
function numPages() {
    last_page = Math.ceil(curArray.length / records_per_page);
}
/***************************************************************************************************************************
                                                BUTTONS OF PAGINATION
****************************************************************************************************************************/
var listItem;
var linkItem;
var spanItem;
// Next-Prev pagination buttons
function createElements(val) {
    // Create <li>
    listItem = document.createElement('li');
    // Create <a>
    linkItem = document.createElement('a');
    // Adding class to both tags
    linkItem.className += "page-link";
    listItem.className += "page-item";
    // Adding ref to <a> with the numbre of pagination
    if (val == 'right') {
        linkItem.href += "javascript:nextPage()";
    } else if (val == 'left') {
        linkItem.href += "javascript:prevPage()";
    }
    spanItem = document.createElement('span');
    // Adding the number (text) on <a>
    linkItem.appendChild(spanItem);
    // Adding <a> on his own <li>
    listItem.appendChild(linkItem);
    // Finally add <li> item on <ul>
    pagNav.appendChild(listItem);
    if (val == 'right') {
        spanItem.innerHTML = "&raquo;";
    } else if (val == 'left') {
        spanItem.innerHTML = "&laquo;";
    }
}
// Number pag buttons
function generatePaginationNum(n, m) {
    pagNav.innerHTML = ""
    createElements('left');
    // Iterative method for list item creation
    for (n; n <= m; n++) {
        // Create <li>
        listItem = document.createElement('li');
        // Create <a>
        linkItem = document.createElement('a');
        // Adding class to both tags
        linkItem.className += "page-link";
        // Using a conditional for listItem
        if (n == current_page) {
            listItem.className += "page-item active";
        } else {
            listItem.className += "page-item";
        }
        // Adding ref to <a> with the numbre of pagination
        linkItem.id += n;
        linkItem.name += "tagA";
        linkItem.href += "javascript:aListener()";
        // Adding the number (text) on <a>
        linkItem.appendChild(document.createTextNode(n));
        // Adding <a> on his own <li>
        listItem.appendChild(linkItem);
        // Finally add <li> item on <ul>
        pagNav.appendChild(listItem);
    }
    createElements('right');
}
// Rotate the numbres of the pagination, so we see 9 pag always
function numPerPagination() {
    if (current_page < 5) {
        if (last_page < 9) {
            generatePaginationNum(1, last_page);
        } else {
            generatePaginationNum(1, 9);
        }
    } else {
        if (current_page + 3 >= last_page) {
            if (last_page - 8 < 1) {
                generatePaginationNum(1, last_page);
            } else {
                generatePaginationNum(last_page - 8, last_page);
            }
        } else {
            generatePaginationNum(current_page - 4, current_page + 4);
        }
    }
}
// Change records per page
function changeTotalRecords() {
    var new_records = document.getElementById("elements").value;
    records_per_page = new_records;
    init(1);
}
/********************************************************END*******************************************************************/