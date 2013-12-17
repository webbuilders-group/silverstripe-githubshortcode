<p class="gitHubButtonGroup $Layout">
    <% if $Show!='both' && $Show!='stars' %>
        <span class="gitHubButton gitHubStargazersButton">
            <a href="https://github.com/$Repository.ATT" title="Star on GitHub" class="gitHubStarButton" target="_blank"><!-- --></a>
            <span class="count">$Stargazers</span>
        </span>
    <% end_if %>
    
    <% if $Show!='both' && $Show!='forks' %>
        <span class="gitHubButton gitHubForkButton">
            <a href="https://github.com/$Repository.ATT" title="Fork on GitHub" class="gitHubForkButton" target="_blank"><!-- --></a>
            <span class="count">$Forks</span>
        </span>
    <% end_if %>
</p>