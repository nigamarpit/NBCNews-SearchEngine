import networkx as nx
G = nx.read_edgelist("edges.txt", create_using=nx.DiGraph())
pr = nx.pagerank(G, alpha=0.85, personalization=None, max_iter=30, tol=1e-06, nstart=None, weight='weight',dangling=None)
f=open('external_pageRankFile','w')
for k in pr.keys():
	f.write(k+'='+str(pr[k])+'\n')
print('external_pageRankFile created successfully!')
f.close()
