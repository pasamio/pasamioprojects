/**
 * @author moffats
 */
function toggleNode(node) 
{
  var nodeArray = node.childNodes;
  for(i=0; i < nodeArray.length; i++)
  {
    node = nodeArray[i];
    if (node.tagName && node.tagName.toLowerCase() == 'div')
      node.style.display = (node.style.display == 'block') ? 'none' : 'block';
  }
}

function openNode(node)
{
  var nodeArray = node.childNodes;
  for(i=0; i < nodeArray.length; i++)
  {
    node = nodeArray[i];
    if (node.tagName && node.tagName.toLowerCase() == 'div')
      node.style.display = 'block';
  }
}

function closeNode(node)
{
  var nodeArray = node.childNodes;
  for(i=0; i < nodeArray.length; i++)
  {
    node = nodeArray[i];
    if (node.tagName && node.tagName.toLowerCase() == 'div')
      node.style.display = 'none';
  }
}

function showNode(node)
{
  if (!node) return;
  if (!node.parentNode) return;
  node = node.parentNode;
  while (node.tagName.toLowerCase() == 'div')
  {
    openNode(node);
    node = node.parentNode;
  }
}

function setBaseDN(value) {
	element = document.getElementById('base_dn');
	element.value = value;
	return false;
}

function setUserDN(value) {
	element = document.getElementById('users_dn');
	element.value = value;
	return false;
}