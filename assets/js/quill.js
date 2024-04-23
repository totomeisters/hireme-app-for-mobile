  var quill = new Quill('#editor', {
    modules: {
      toolbar: [
        ['bold', 'italic'],
        [{ list: 'ordered' }, { list: 'bullet' }]
      ]
    },
    scrollingContainer: '#content-container',
    placeholder: 'Enter Job Description...',
    theme: 'snow'
  });
  
  $('[data-toggle="tooltip"]').tooltip();
