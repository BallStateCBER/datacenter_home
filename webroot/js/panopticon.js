class Panopticon {
  constructor() {
    document.querySelectorAll('td.check-status').forEach(cell => this.checkStatus(cell));
    document.querySelectorAll('a.issues').forEach(link => this.setUpIssuesLinks(link));
    document.querySelectorAll('td.check-auto-deploy').forEach(cell => this.checkAutoDeploy(cell));
  }

  insertAfter(newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
  }

  checkStatus(cell) {
    cell.innerHTML = '<i class="fas fa-circle-notch fa-spin" title="Loading"></i>';
    const url = '/pages/check-status.json?url=' + encodeURIComponent(cell.dataset.url);
    fetch(url)
      .then(response => response.json())
      .then(response => {
        const success = response.result.status.search('200 OK') > -1;
        const className = success ? 'fas fa-check-circle' : 'fas fa-minus-circle';
        const title = success ? '200 OK' : response.result.status;
        cell.innerHTML = `<i class="${className}" title="${title}"></i>`
        if (response.result.debug) {
          cell.innerHTML += ' <span class="debug">(debug)</span>';
        }
      })
      .catch(error => {
        console.error(`Error loading ${url}`, error);
        cell.innerHTML = `<i class="fas fa-exclamation-triangle" title="Error, check console for details"></i>`;
      });
  }

  setUpIssuesLinks(link) {
    link.addEventListener('click', event => {
      event.preventDefault();
      const link = event.target;
      const repo = link.dataset.repo;

      const issuesRow = document.getElementById(`${repo}-issues`);
      if (issuesRow) {
        this.toggleIssuesRow(issuesRow);
        return;
      }

      this.fetchIssues(link, repo);
    });
  }

  checkAutoDeploy(cell) {
    cell.innerHTML = '<i class="fas fa-circle-notch fa-spin" title="Loading"></i>';
    const url = '/pages/auto-deploy-check.json?site=' + encodeURIComponent(cell.dataset.site);
    fetch(url)
      .then(response => response.json())
      .then(response => {
        const success = response.result;
        const className = success ? 'fas fa-check-circle' : 'fas fa-minus-circle';
        const title = success ? 'Auto-deployed' : 'Not auto-deployed';
        cell.innerHTML = `<i class="${className}" title="${title}"></i>`;
      })
      .catch(error => {
        cell.innerHTML = `<i class="fas fa-exclamation-triangle" title="Error, check console for details"></i>`;
        console.log(`Error loading ${url}`, error);
      });
  }

  toggleIssuesRow(issuesRow) {
    if (issuesRow.style.display === 'none') {
      issuesRow.style.display = 'table-row';
      slideDown(issuesRow.querySelector('ul'));
    } else {
      slideUp(issuesRow.querySelector('ul'), 500);
      setTimeout(function () {
        issuesRow.style.display = 'none';
      }, 500);
    }
  }

  fetchIssues(link, repo) {
    // Query GitHub
    const url = `https://api.github.com/repos/BallStateCBER/${repo}/issues`;
    fetch(url)
      .then(response => response.json())
      .then(response => {
        const tr = link.closest('tr');
        const colspan = tr.childElementCount - 1;
        const newRow = document.createElement('tr');
        newRow.id = `${repo}-issues`;
        newRow.classList.add('issues');
        newRow.innerHTML = `<td></td><td colspan="${colspan}"><ul style="display: none;"></ul></td>`;
        this.insertAfter(newRow, tr);
        const ul = document.querySelector(`#${repo}-issues ul`);
        if (response) {
          response.forEach(issue => {
            ul.innerHTML += `<li><a href="${issue.html_url}">${issue.title}</a></li>`;
          });
        }
        ul.innerHTML += `<li><a href="https://github.com/BallStateCBER/${repo}/issues/new">Add a new issue</a></li>`;
        slideDown(ul);
      })
      .catch(error => {
        link.classList.add('error');
        console.log(`Error loading ${url}`, error);
      });
  }
}

