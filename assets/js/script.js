document.addEventListener('DOMContentLoaded', function () {
    const burger = document.getElementById('burgerBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    if (burger && sidebar && overlay) {
        burger.addEventListener('click', function () {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        });
        overlay.addEventListener('click', function () {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        });
    }
    document.querySelectorAll('.confirm-delete').forEach(function (link) {
        link.addEventListener('click', function (e) {
            const label = link.getAttribute('data-label') || 'cet élément';
            if (!confirm('Voulez-vous vraiment supprimer ' + label + ' ? Cette action est irréversible.')) {
                e.preventDefault();
            }
        });
    });
    const imgInput = document.getElementById('imageInput');
    const imgPreview = document.getElementById('imagePreview');
    if (imgInput && imgPreview) {
        imgInput.addEventListener('change', function () {
            const file = imgInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imgPreview.innerHTML = '<img src="' + e.target.result + '" alt="Aperçu">';
                };
                reader.readAsDataURL(file);
            }
        });
    }
    document.querySelectorAll('.alert').forEach(function (alertBox) {
        setTimeout(function () {
            alertBox.style.transition = 'opacity .4s';
            alertBox.style.opacity = '0';
            setTimeout(function () { alertBox.remove(); }, 400);
        }, 5000);
    });
    initFactureLines();
});

function initFactureLines() {
    const linesBody = document.getElementById('lineItemsBody');
    const addBtn = document.getElementById('addLineBtn');
    const totalDisplay = document.getElementById('invoiceTotalDisplay');
    const totalHidden = document.getElementById('invoiceTotalHidden');
    if (!linesBody || !addBtn) return;
    let lineIndex = linesBody.querySelectorAll('.line-item-row').length;
    function recalcTotal() {
        let total = 0;
        linesBody.querySelectorAll('.line-item-row').forEach(function (row) {
            const qte = parseFloat(row.querySelector('.line-qte').value) || 0;
            const prix = parseFloat(row.querySelector('.line-prix').value) || 0;
            const sousTotal = qte * prix;
            row.querySelector('.line-sous-total').textContent = sousTotal.toLocaleString('fr-FR', {minimumFractionDigits:2, maximumFractionDigits:2}) + ' FCFA';
            total += sousTotal;
        });
        if (totalDisplay) totalDisplay.textContent = total.toLocaleString('fr-FR', {minimumFractionDigits:2, maximumFractionDigits:2}) + ' FCFA';
        if (totalHidden) totalHidden.value = total.toFixed(2);
    }
    function bindRow(row) {
        row.querySelectorAll('.line-qte, .line-prix').forEach(function (input) {
            input.addEventListener('input', recalcTotal);
        });
        const removeBtn = row.querySelector('.remove-line');
        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                if (linesBody.querySelectorAll('.line-item-row').length > 1) {
                    row.remove();
                    recalcTotal();
                } else {
                    alert('Une facture doit contenir au moins une ligne.');
                }
            });
        }
        const produitSelect = row.querySelector('.line-produit');
        if (produitSelect) {
            produitSelect.addEventListener('change', function () {
                const opt = produitSelect.selectedOptions[0];
                if (opt && opt.dataset.prix) {
                    row.querySelector('.line-designation').value = opt.dataset.nom || '';
                    row.querySelector('.line-prix').value = opt.dataset.prix;
                    recalcTotal();
                }
            });
        }
    }
    addBtn.addEventListener('click', function () {
        const template = document.getElementById('lineRowTemplate').innerHTML.replace(/__INDEX__/g, lineIndex);
        const tmp = document.createElement('tbody');
        tmp.innerHTML = template;
        const newRow = tmp.querySelector('.line-item-row');
        linesBody.appendChild(newRow);
        bindRow(newRow);
        lineIndex++;
    });
    linesBody.querySelectorAll('.line-item-row').forEach(bindRow);
    recalcTotal();
}
