<% if $SiteConfig.EnableFeedbackAssist %>
<script src="https://www.onegov.nsw.gov.au/CDN/feedbackassist/feedbackassist.v1.min.js"<% if $SiteConfig.FeedbackAssistHash %> integrity="$SiteConfig.FeedbackAssistHash" crossorigin="anonymous"<% end_if %>></script>
<script>caBoootstrap.init("https://feedbackassist.onegov.nsw.gov.au/feedbackassist");</script>
<% end_if %>
