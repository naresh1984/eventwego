# Configure common attachment storage
  # This approach allows more flexibility in defining, and potentially moving,
  # a common storage root for multiple models.  If unneeded, just replace
  # :base in the :path parameter with the actual path
  Paperclip.interpolates :base do |attachment, style|
   # /path/to/persistent/storage
    # A relative path from the Rails.root directory should work as well
  end
