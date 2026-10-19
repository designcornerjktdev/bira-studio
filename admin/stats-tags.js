/**
 * Field "Stats" di editor Project: ketik → Enter / koma → jadi tag (tanpa saran).
 * Nilai tetap disimpan di input asli sebagai teks dipisah koma ("20M+ Views, 150+ Content pieces").
 */
(function () {
  function init(input) {
    if (input.dataset.biraTags) return;
    input.dataset.biraTags = '1';

    var box = document.createElement('div');
    box.className = 'bira-tags';
    var list = document.createElement('span');
    list.className = 'bira-tags__list';
    var entry = document.createElement('input');
    entry.type = 'text';
    entry.className = 'bira-tags__entry';
    entry.placeholder = input.placeholder;
    entry.setAttribute('aria-label', 'Add stat');
    box.append(list, entry);
    input.type = 'hidden';
    input.after(box);

    var values = input.value.split(',').map(function (v) { return v.trim(); }).filter(Boolean);

    function sync() {
      input.value = values.join(', ');
      input.dispatchEvent(new Event('change', { bubbles: true }));
      list.textContent = '';
      values.forEach(function (value, i) {
        var tag = document.createElement('span');
        tag.className = 'bira-tags__tag';
        tag.textContent = value;
        var remove = document.createElement('button');
        remove.type = 'button';
        remove.className = 'bira-tags__remove';
        remove.textContent = '×';
        remove.setAttribute('aria-label', 'Remove ' + value);
        remove.addEventListener('click', function () {
          values.splice(i, 1);
          sync();
          entry.focus();
        });
        tag.append(remove);
        list.append(tag);
      });
    }

    function add() {
      entry.value.split(',').forEach(function (v) {
        v = v.trim();
        if (v && values.indexOf(v) === -1) values.push(v);
      });
      entry.value = '';
      sync();
    }

    entry.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ',') {
        e.preventDefault(); // Enter jangan submit / publish post
        add();
      } else if (e.key === 'Backspace' && !entry.value && values.length) {
        values.pop();
        sync();
      }
    });
    entry.addEventListener('blur', add); // teks yang belum di-Enter tetap tersimpan
    entry.addEventListener('paste', function () { setTimeout(add, 0); });
    box.addEventListener('click', function (e) { if (e.target === box) entry.focus(); });

    sync();
  }

  function scan() {
    document.querySelectorAll('.bira-stats-field input[type="text"]:not(.bira-tags__entry)').forEach(init);
  }

  // Meta box ACF bisa muncul belakangan di editor blok, jadi pantau DOM.
  scan();
  new MutationObserver(scan).observe(document.body, { childList: true, subtree: true });
})();
