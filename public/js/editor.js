CKEDITOR.replace('content', {
    toolbar: [
        {
            name: 'basic',
            items: [
                'Bold', 'Italic', 'Underline', 'Strikethrough', 'Subscript', 'Superscript',
                'RemoveFormat', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock',
                'NumberedList', 'BulletedList', 'Indent', 'Outdent', 'Link', 'Unlink',
                'Image', 'Table', 'Code', 'Maximize'
            ]
        }
    ],
    height: '400px',
    filebrowserBrowseUrl: '/images',
    filebrowserUploadUrl: '/images'
});
