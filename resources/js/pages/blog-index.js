const articleListContainer = $('#article-list-container');
const paginationContainer = $('#pagination-container');

function fetchArticles(url) {
  console.log('Richiesta AJAX a:', url);

  articleListContainer.css('opacity', 0.5);
  paginationContainer.empty();

  $.ajax({
    url: url,
    type: 'GET',
    dataType: 'json',
    headers: {
      Accept: 'application/json',
    },
  })
    .done(function (response) {
      articleListContainer.html(response.articles_html);
      paginationContainer.html(response.pagination_html);
    })
    .fail(function () {
      alert('Si è verificato un errore durante il caricamento degli articoli.');
    })
    .always(function () {
      articleListContainer.css('opacity', 1);
      $('html, body').animate(
        { scrollTop: articleListContainer.offset().top - 150 },
        300
      );
    });
}

// La delegazione degli eventi
$(document).on('change', 'input[name="rubric_filter"]', function () {
  // ... la tua logica ...
  const selectedRubricValue = $(this).val();
  const currentParams = new URLSearchParams(window.location.search);
  const sortBy = currentParams.get('ordina_per') || 'published_at_desc';

  // NOTA: Dobbiamo passare l'URL di base da Blade a JS. Lo faremo al passo 3.
  // Per ora, lasciamo un placeholder.
  let newUrl = new URL(window.blogIndexUrl); // Usiamo una variabile globale
  newUrl.searchParams.set('ordina_per', sortBy);
  if (selectedRubricValue) {
    newUrl.searchParams.set('rubrica', selectedRubricValue);
  }
  history.pushState(null, '', newUrl.toString());
  fetchArticles(newUrl.toString());
});

$(document).on(
  'click',
  '#sortTabs a, #pagination-container .pagination a',
  function (e) {
    e.preventDefault();
    const url = $(this).attr('href');
    history.pushState(null, '', url);
    fetchArticles(url);
  }
);
