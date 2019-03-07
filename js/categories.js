// If category name is submitted then make ajax request to get list of articles
function getArticles(){
    var url = document.getElementById('categoryForm').getAttribute("action");
    var categoryInput = document.getElementById("category").value;
    
    if (!categoryInput.trim()) {
        document.getElementById("articlesList").innerHTML = "<ul><li>Please enter a category name</li></ul>";
        return;
    }
    
    fetch(url, {
        headers: { "Content-Type": "application/json; charset=utf-8" },
        method: 'POST',
        body: JSON.stringify({category: categoryInput})
    })
    .then((response) => response.json())
    .then( function(data){
        var articlesHtml = "<ul>";
        if(data.error){
            articlesHtml += `<li>${data.error}</li>`;
        }
        else{
            var articles = data.articles;
            
            if(Object.keys(articles).length){
                Object.keys(articles).forEach(key => {
                    articlesHtml += `<li>${key} - ${articles[key]}</li>`; 
                });
            }
            else{
                articlesHtml += "<li>No Results</li>"
            }
        }
        articlesHtml += "</ul>";
        document.getElementById("articlesList").innerHTML = articlesHtml;
    })
    .catch((error) => { alert(`There was an error ${error}`); console.log(error);})
}

document.getElementById('categoryForm').addEventListener('submit', function(event){
    event.preventDefault();
    getArticles();
});

