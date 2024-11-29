const BASE_URL = 'http://api.cc.localhost/';

function get(path){
    return fetch(BASE_URL + path, {
        method: 'GET',
        headers: {
            Accept: 'application/json'
        }
    }).then(res => res.json())
}

function courseTemplate(id, name, description, image, category){
    return `
      <div class="course-card" data-id="${id}">
            <img src="${image}" alt="${name}">
            <span class="tag">${category}</span>
            <h3>${name}</h3>
            <p>${description}</p>
        </div>
    `
}